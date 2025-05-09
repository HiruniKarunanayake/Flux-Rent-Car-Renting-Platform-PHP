<?php
// Import the Database class
require_once 'config.php';

class VehicleOwner
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $dob;

    public function __construct($firstName = null, $lastName = null, $email = null, $phone = null, $password = null, $dob = null)
    {
        if ($firstName !== null) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->phone = $phone;
            $this->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $this->dob = $dob;
        }
    }

    public function register()
    {
        // Prepare an SQL statement for inserting data
        $stmt = "INSERT INTO vehicle_owners (first_name, last_name, email, phone, password, dob) 
                  VALUES ('$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->password', '$this->dob')";

        // Use the Database class to execute the query
        return Database::iud($stmt);
    }

    // Check if the email already exists
    public static function emailExists($email)
    {
        $query = "SELECT * FROM vehicle_owners WHERE email='$email'";
        $result = Database::search($query);
        return $result->num_rows > 0; // Returns true if email exists
    }
    public function login($email, $password)
    {
        // Prepare the SQL statement to find the vehicle owner
        $query = "SELECT * FROM vehicle_owners WHERE email='$email'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $ownerData = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $ownerData['password'])) {
                return $ownerData; // Return vehicle owner data if login is successful
            }
        }
        return false; // Return false if login fails
    }
    public static function getProfile($ownerId)
    {
        $query = "SELECT first_name, last_name, email, phone, dob 
                  FROM vehicle_owners WHERE id = '" . $ownerId . "'";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    public function updateProfile($userId, $firstName, $lastName, $email, $phone, $password, $dob)
    {
        // Hash password if it's being changed
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        // Prepare the SQL update query
        $query = "UPDATE vehicle_owners SET 
        first_name='$firstName', 
        last_name='$lastName', 
        email='$email', 
        phone='$phone', 
        dob='$dob'";

        // Only update the password if it's provided
        if (!empty($password)) {
            $query .= ", password='$passwordHash'";
        }

        $query .= " WHERE id='$userId'";

        // Execute the update query
        return Database::iud($query);
    }
    public static function deleteAccount($userId)
{
    $vehicleQuery = "SELECT id FROM vehicles WHERE owner_id = '$userId'";
    $vehicles = Database::search($vehicleQuery);

    if ($vehicles) {
        foreach ($vehicles as $vehicle) {
            $vehicleId = $vehicle['id'];

            $deleteBookingsQuery = "DELETE FROM bookings WHERE vehicle_id = '$vehicleId'";
            Database::iud($deleteBookingsQuery);
            
            $deleteVehicleQuery = "DELETE FROM vehicles WHERE id = '$vehicleId'";
            Database::iud($deleteVehicleQuery);
        }
    }

    // 4. Finally, delete the vehicle owner
    $deleteOwnerQuery = "DELETE FROM vehicle_owners WHERE id = '$userId'";
    return Database::iud($deleteOwnerQuery);
}

}
