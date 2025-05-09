<?php
require_once 'config.php';

class Booking
{
    private $vehicleId;
    private $startDate;
    private $endDate;
    private $location;
    private $withDriver;
    private $userId;
    private $status;
    private $driverId;
    private $amount;

    public function __construct($vehicleId = null, $startDate = null, $endDate = null, $location = null, $withDriver = null, $userId = null, $status = 'pending', $driverId = null, $amount = null)
    {
        $this->vehicleId = $vehicleId;
        $this->amount = $amount;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->location = $location;
        $this->withDriver = $withDriver;
        $this->userId = $userId;
        $this->driverId = $driverId;
        $this->status = $status;
    }

    // Function to add a booking
    public function addBooking()
    {
        // Fetch the vehicle price from the vehicles table based on vehicle_id
        $vehicleQuery = "SELECT price FROM vehicles WHERE id = '{$this->vehicleId}'";
        $result = Database::search($vehicleQuery);

        if ($result && $result->num_rows > 0) {
            $vehicle = $result->fetch_assoc();
            $pricePerDay = $vehicle['price'];

            // Calculate the number of rental days
            $startDate = new DateTime($this->startDate);
            $endDate = new DateTime($this->endDate);
            $days = $startDate->diff($endDate)->days + 1;

            // Calculate the total rental amount
            $this->amount = $days * $pricePerDay;

            // Convert boolean to integer for 'with_driver'
            $withDriverValue = $this->withDriver ? 1 : 0;

            // Insert the booking into the bookings table
            $query = "INSERT INTO bookings (vehicle_id, start_date, end_date, location, with_driver, user_id, status, amount) 
                  VALUES ('{$this->vehicleId}', '{$this->startDate}', '{$this->endDate}', '{$this->location}', '{$withDriverValue}', '{$this->userId}', '{$this->status}', '{$this->amount}')";
            return Database::iud($query);
        } else {
            return "Vehicle not found";
        }
    }
    public static function getCustomerBookings($customerId)
    {
        $query = "
        SELECT b.*, v.make, v.model 
        FROM bookings b
        JOIN vehicles v ON b.vehicle_id = v.id
        WHERE b.user_id = '" . $customerId . "'
        ";

        $result = Database::search($query);

        if (!$result) {
            die("Query failed: " . Database::$connection->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public static function getBookingsByUserIdAndRole($userId, $role)
    {
        $query = '';

        // Fetch bookings based on the role
        switch ($role) {
            case 'customer':
                // For customers, search using user_id
                $query = "SELECT * FROM bookings WHERE user_id = '$userId'";
                break;
            case 'driver':
                // For vehicle drivers, search using driver_id
                $query = "SELECT * FROM bookings WHERE driver_id = '$userId'";
                break;
            case 'owner':
                // For vehicle owners, first get the vehicle(s) they own
                $vehicleQuery = "SELECT id FROM vehicles WHERE owner = '$userId'";
                $vehiclesResult = Database::search($vehicleQuery);

                // Gather vehicle IDs into an array
                $vehicleIds = [];
                while ($row = $vehiclesResult->fetch_assoc()) {
                    $vehicleIds[] = $row['id'];
                }

                // Convert vehicle IDs into a comma-separated string for the query
                $vehicleIdList = implode(',', $vehicleIds);

                // Search for bookings related to these vehicles
                if (!empty($vehicleIdList)) {
                    $query = "SELECT * FROM bookings WHERE vehicle_id IN ($vehicleIdList)";
                } else {
                    return []; // Return empty array if no vehicles found
                }
                break;
            default:
                return []; // Return empty if role is invalid
        }

        // Execute the search query and fetch all matching records
        $result = Database::search($query);
        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    }
    public function updateBooking($bookingId)
    {
        $currentUserId = $_SESSION['user_id'];

        // Fetch the existing booking to validate and ensure the current user is the owner
        $bookingQuery = "SELECT user_id FROM bookings WHERE id = '{$bookingId}'";
        $bookingResult = Database::search($bookingQuery);

        if ($bookingResult && $bookingResult->num_rows > 0) {
            $booking = $bookingResult->fetch_assoc();

            // Check if the logged-in user is the owner of the booking
            if ($booking['user_id'] !== $currentUserId) {
                return "Unauthorized action.";
            }

            // Fetch the vehicle price from the vehicles table based on vehicle_id
            $vehicleQuery = "SELECT price FROM vehicles WHERE id = '{$this->vehicleId}'";
            $vehicleResult = Database::search($vehicleQuery);

            if ($vehicleResult && $vehicleResult->num_rows > 0) {
                $vehicle = $vehicleResult->fetch_assoc();
                $pricePerDay = $vehicle['price'];

                // Calculate the number of rental days
                $startDate = new DateTime($this->startDate);
                $endDate = new DateTime($this->endDate);
                $days = $startDate->diff($endDate)->days + 1;

                // Calculate the total rental amount
                $this->amount = $days * $pricePerDay;

                // Convert boolean to integer for 'with_driver'
                $withDriverValue = $this->withDriver ? 1 : 0;

                // Update the booking in the bookings table
                $updateQuery = "UPDATE bookings 
                            SET vehicle_id = '{$this->vehicleId}', start_date = '{$this->startDate}', end_date = '{$this->endDate}', location = '{$this->location}', 
                            with_driver = '{$withDriverValue}', amount = '{$this->amount}', status = '{$this->status}' 
                            WHERE id = '{$bookingId}'";
                return Database::iud($updateQuery);
            } else {
                return "Vehicle not found";
            }
        } else {
            return "Booking not found";
        }
    }

    public function getBookingDetails($bookingId)
    {
        // Prepare the query to join bookings and vehicles based on the vehicle_id
        $query = "
        SELECT bookings.*, vehicles.make AS vehicle_make, vehicles.model AS vehicle_model, 
               vehicles.year AS vehicle_year, vehicles.color AS vehicle_color, vehicles.price AS vehicle_price
        FROM bookings
        JOIN vehicles ON bookings.vehicle_id = vehicles.id
        WHERE bookings.id = '{$bookingId}'
    ";

        // Execute the query
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            // Fetch the booking details including vehicle details
            $bookingDetails = $result->fetch_assoc();

            return $bookingDetails; // Return the combined booking and vehicle details
        } else {
            return null; // Return null if booking is not found
        }
    }
    public static function deleteBooking($bookingId, $userId)
    {
        // Check if the booking belongs to the user and is not approved
        $query = "SELECT * FROM bookings WHERE id = '$bookingId' AND user_id = '$userId' AND status != 'approved'";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            // Proceed with the deletion
            $deleteQuery = "DELETE FROM bookings WHERE id = '$bookingId'";
            return Database::iud($deleteQuery);
        } else {
            return false; // Unauthorized attempt or booking is approved
        }
    }
}
