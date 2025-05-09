<?php
// Import the Database class
require_once 'config.php';

class VehicleDriver
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $dob;
    private $emergencyContactName;
    private $emergencyContactPhone;
    private $drivingExperience;

    public function __construct($firstName = null, $lastName = null, $email = null, $phone = null, $password = null, $dob = null, $emergencyContactName = null, $emergencyContactPhone = null, $drivingExperience = null) {
        if ($firstName !== null) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->phone = $phone;
            $this->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $this->dob = $dob;
            $this->emergencyContactName = $emergencyContactName;
            $this->emergencyContactPhone = $emergencyContactPhone;
            $this->drivingExperience = $drivingExperience;
        }
    }
    

    public function register()
    {
        // Prepare an SQL statement for inserting data into the 'vehicle_drivers' table
        $stmt = "INSERT INTO vehicle_drivers (first_name, last_name, email, phone, password, dob, emergency_contact_name, emergency_contact_phone, driving_experience) 
                  VALUES ('$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->password', '$this->dob', '$this->emergencyContactName', '$this->emergencyContactPhone', '$this->drivingExperience')";
        
        // Use the Database class to execute the query
        return Database::iud($stmt);
    }

    // Check if the email already exists
    public static function emailExists($email)
    {
        $query = "SELECT * FROM vehicle_drivers WHERE email='$email'";
        $result = Database::search($query);
        return $result->num_rows > 0; // Returns true if email exists
    }
    public function login($email, $password)
    {
        // Prepare the SQL statement to find the driver
        $query = "SELECT * FROM vehicle_drivers WHERE email='$email'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $driverData = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $driverData['password'])) {
                return $driverData; // Return driver data if login is successful
            }
        }
        return false; // Return false if login fails
    }
    public static function getProfile($driverId)
    {
        $query = "SELECT first_name, last_name, email, phone, dob, emergency_contact_name, emergency_contact_phone, driving_experience 
                  FROM vehicle_drivers WHERE id = '".$driverId."'";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    public function updateProfile($userId, $firstName, $lastName, $email, $phone, $password, $dob, $emergencyContactName, $emergencyContactPhone, $drivingExperience)
{
    // Hash password if it's being changed
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    // Prepare the SQL update query
    $query = "UPDATE vehicle_drivers SET 
        first_name='$firstName', 
        last_name='$lastName', 
        email='$email', 
        phone='$phone', 
        dob='$dob', 
        emergency_contact_name='$emergencyContactName', 
        emergency_contact_phone='$emergencyContactPhone', 
        driving_experience='$drivingExperience'";

    // Only update the password if it's provided
    if (!empty($password)) {
        $query .= ", password='$passwordHash'";
    }

    $query .= " WHERE id='$userId'";

    // Execute the update query
    return Database::iud($query);
}
public static function deleteAccount($userId) {
    // Delete the vehicle driver record
    $query = "DELETE FROM vehicle_drivers WHERE id = '$userId'";
    return Database::iud($query);
}

}
?>
