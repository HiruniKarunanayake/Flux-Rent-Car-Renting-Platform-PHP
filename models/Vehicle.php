<?php
require_once 'config.php';

class Vehicle
{
    // Add vehicle to the database
    public function addVehicle($make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $images, $price, $description)
    {
        // Use a prepared statement to avoid SQL injection
        $query = "INSERT INTO vehicles (make, model, year, type, fuel_type, transmission, seating_capacity, mileage, color, owner, driver,price,description) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('sssssssssssss', $make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $price, $description);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Get the last inserted vehicle ID
        $vehicleId = $stmt->insert_id;

        // Process image uploads
        if ($this->uploadImages($vehicleId, $images)) {
            return true;
        }
        return false;
    }

    // Upload vehicle images
    private function uploadImages($vehicleId, $images)
    {
        $uploadDir = 'uploads/vehicles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($images['tmp_name'] as $key => $tmpName) {
            if ($images['error'][$key] === UPLOAD_ERR_OK) {
                $imageName = $vehicleId . '_' . time() . '_' . basename($images['name'][$key]);
                $targetFile = $uploadDir . $imageName;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $query = "INSERT INTO vehicle_images (vehicle_id, image_path) VALUES (?, ?)";
                    $stmt = Database::$connection->prepare($query);

                    if ($stmt === false) {
                        die("Prepare failed: " . Database::$connection->error);
                    }

                    $stmt->bind_param('is', $vehicleId, $targetFile);

                    if (!$stmt->execute()) {
                        die("Execute failed: " . $stmt->error);
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    // Fetch all vehicles
    public function getAllVehicles()
    {
        // Query to select vehicles and their associated images
        $query = "
        SELECT v.*, vi.image_path 
        FROM vehicles v
        LEFT JOIN vehicle_images vi 
        ON v.id = vi.vehicle_id
    ";

        // Execute the query
        $result = Database::search($query);

        // Check for errors
        if (!$result) {
            die("Query failed: " . Database::$connection->error);
        }

        // Fetch all results
        $vehicles = [];
        while ($row = $result->fetch_assoc()) {
            // Check if vehicle ID already exists in the array
            if (!isset($vehicles[$row['id']])) {
                // If not, initialize the vehicle entry with its details
                $vehicles[$row['id']] = [
                    'id' => $row['id'],
                    'make' => $row['make'],
                    'model' => $row['model'],
                    'price' => $row['price'],
                    'images' => []
                ];
            }

            // Append the image if it exists
            if (!empty($row['image_path'])) {
                $vehicles[$row['id']]['images'][] = $row['image_path'];
            }
        }

        // Return the array of vehicles with their associated images
        return array_values($vehicles);
    }


    // Fetch vehicle by ID
    public static function getVehicleById($vehicleId)
    {
        Database::setUpConnection(); // Ensure connection is established
        $query = "SELECT * FROM vehicles WHERE id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteVehicleById($id)
    {

        // Delete all records in the bookings table for the vehicle_id
        $deleteBookingsQuery = "DELETE FROM bookings WHERE vehicle_id = $id";
        $deleteBookingsResult = Database::iud($deleteBookingsQuery);

        if ($deleteBookingsResult !== true) {
            die("Failed to delete bookings: " . $deleteBookingsResult);
        }

        // Delete the vehicle from the vehicles table
        $deleteVehicleQuery = "DELETE FROM vehicles WHERE id = $id";
        $deleteVehicleResult = Database::iud($deleteVehicleQuery);

        if ($deleteVehicleResult !== true) {
            die("Failed to delete vehicle: " . $deleteVehicleResult);
        }

        return true; // Successfully deleted both bookings and the vehicle
    }


    public function adminDeleteVehicleById($id)
    {
        $deleteImagesQuery = "DELETE FROM vehicle_images WHERE vehicle_id = '$id'";
        Database::iud($deleteImagesQuery);

        $deleteBookingQuery = "DELETE FROM bookings WHERE vehicle_id = '$id'";
        Database::iud($deleteBookingQuery);

        $deleteVehicleQuery = "DELETE FROM vehicles WHERE id = '$id'";
        return Database::iud($deleteVehicleQuery);
    }


    public function getAvailableVehicles($startDate, $endDate)
    {
        // Query to select available vehicles and their associated images
        $query = "
    SELECT v.*, vi.image_path 
    FROM vehicles v
    LEFT JOIN vehicle_images vi 
    ON v.id = vi.vehicle_id
    WHERE v.id NOT IN (
        SELECT vehicle_id 
        FROM bookings 
        WHERE (start_date <= '$endDate' AND end_date >= '$startDate') 
        OR (start_date <= '$startDate' AND end_date >= '$endDate')
    )
    ";

        // Execute the query using the Database class
        $result = Database::search($query);

        // Check for errors
        if (!$result) {
            die("Query failed: " . Database::$connection->error);
        }

        // Initialize an array to store vehicles
        $vehicles = [];

        // Process each row of the result set
        while ($row = $result->fetch_assoc()) {
            // If the vehicle ID is not yet in the vehicles array, add it
            if (!isset($vehicles[$row['id']])) {
                $vehicles[$row['id']] = [
                    'id' => $row['id'],
                    'make' => $row['make'],
                    'model' => $row['model'],
                    'price' => $row['price'],
                    'images' => []
                ];
            }

            // Append the image to the images array if available
            if (!empty($row['image_path'])) {
                $vehicles[$row['id']]['images'][] = $row['image_path'];
            }
        }

        // Return the vehicles array
        return array_values($vehicles);
    }




    // Update vehicle details
    public function updateVehicle($vehicleId, $make, $model, $year, $type, $fuelType,$price, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $images = null)
    {
        $query = "UPDATE vehicles 
                  SET make = ?, model = ?,price=?, year = ?, type = ?, fuel_type = ?, transmission = ?, seating_capacity = ?, mileage = ?, color = ?, owner = ?, driver = ? 
                  WHERE id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('ssisssssssssi', $make, $model,$price, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        if ($images && $images['tmp_name'][0] != '') {
            $this->deleteVehicleImages($vehicleId);
            if (!$this->uploadImages($vehicleId, $images)) {
                return false;
            }
        }
        return true;
    }

    // Delete all images related to a vehicle
    private function deleteVehicleImages($vehicleId)
    {
        $query = "SELECT image_path FROM vehicle_images WHERE vehicle_id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $imagePath = $row['image_path'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $query = "DELETE FROM vehicle_images WHERE vehicle_id = ?";
        $stmt = Database::$connection->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . Database::$connection->error);
        }

        $stmt->bind_param('i', $vehicleId);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
    }
    public function getVehicleWithImages($vehicleId)
    {
        // Prepare SQL statement to retrieve vehicle details
        $sql = "SELECT v.*, i.image_path 
                FROM vehicles v 
                LEFT JOIN vehicle_images i ON v.id = i.vehicle_id 
                WHERE v.id = " . intval($vehicleId);

        // Execute the search query
        $result = Database::search($sql);

        // Fetch vehicle details
        if ($result->num_rows > 0) {
            $vehicle = $result->fetch_assoc();

            $imagesSql = "SELECT image_path FROM vehicle_images WHERE vehicle_id = " . intval($vehicleId);
            $imagesResult = Database::search($imagesSql);

            $vehicle['images'] = [];
            while ($imageRow = $imagesResult->fetch_assoc()) {
                $vehicle['images'][] = $imageRow['image_path'];
            }

            return $vehicle;
        }

        return null; // Vehicle not found
    }
}
