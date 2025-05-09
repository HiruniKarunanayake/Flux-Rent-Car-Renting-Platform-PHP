<?php
// Import the Database class
require_once 'config.php';

class Customer
{
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $dob;
    private $preferredContactMethod;

    public function __construct($firstName = null, $lastName = null, $email = null, $phone = null, $password = null, $dob = null, $preferredContactMethod = null)
    {
        if ($firstName !== null) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->phone = $phone;
            $this->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
            $this->dob = $dob;
            $this->preferredContactMethod = $preferredContactMethod;
        }
    }


    public function register()
    {
        // Prepare an SQL statement for inserting data
        $stmt = "INSERT INTO customers (first_name, last_name, email, phone, password, dob, preferred_contact_method) 
                  VALUES ('$this->firstName', '$this->lastName', '$this->email', '$this->phone', '$this->password', '$this->dob', '$this->preferredContactMethod')";

        // Use the Database class to execute the query
        return Database::iud($stmt);
    }

    // Check if the email already exists
    public static function emailExists($email)
    {
        $query = "SELECT * FROM customers WHERE email='$email'";
        $result = Database::search($query);
        return $result->num_rows > 0; // Returns true if email exists
    }

    public function login($email, $password)
    {
        // Prepare the SQL statement to find the customer
        $query = "SELECT * FROM customers WHERE email='$email'";
        $result = Database::search($query);

        if ($result->num_rows > 0) {
            $customerData = $result->fetch_assoc();

            if (password_verify($password, $customerData['password'])) {
                return $customerData;
            }
        }
        return false;
    }

    public static function getProfile($customerId)
    {
        $query = "SELECT first_name, last_name, email, phone, dob, preferred_contact_method 
                  FROM customers WHERE id = '" . $customerId . "'";
        $result = Database::search($query);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    public function updateProfile($userId, $firstName, $lastName, $email, $phone, $password, $dob, $preferredContactMethod)
    {
        // Hash password if it's being changed
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        // Prepare the SQL update query
        $query = "UPDATE customers SET 
        first_name='$firstName', 
        last_name='$lastName', 
        email='$email', 
        phone='$phone', 
        dob='$dob', 
        preferred_contact_method='$preferredContactMethod'";

        // Only update the password if it's provided
        if (!empty($password)) {
            $query .= ", password='$passwordHash'";
        }

        $query .= " WHERE id='$userId'";

        // Execute the update query
        return Database::iud($query);
    }
    public static function deleteAccount($userId) {
        // Delete the customer record
        $query = "DELETE FROM customers WHERE id = '$userId'";
        return Database::iud($query);
    }

    public function addReview($vehicleId, $customerName, $comment, $rating) {
        try {
            // Validate input
            if (empty($customerName) || empty($comment) || $rating < 1 || $rating > 5) {
                return false;
            }

            // Use the same database method as other functions
            $query = "INSERT INTO reviews (vehicle_id, customer_name, comment, rating, created_at) 
                     VALUES ('$vehicleId', '$customerName', '$comment', '$rating', NOW())";
            
            return Database::iud($query);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getVehicleReviews($vehicleId) {
        try {
            $query = "SELECT * FROM reviews 
                     WHERE vehicle_id = '$vehicleId' 
                     ORDER BY created_at DESC";
            
            $result = Database::search($query);
            $reviews = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $reviews[] = $row;
            }
            
            return $reviews;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getAverageRating($vehicleId) {
        try {
            $query = "SELECT AVG(rating) as average_rating, COUNT(*) as total_reviews 
                     FROM reviews 
                     WHERE vehicle_id = '$vehicleId'";
            
            $result = Database::search($query);
            $row = mysqli_fetch_assoc($result);
            
            return [
                'average_rating' => round($row['average_rating'], 1),
                'total_reviews' => $row['total_reviews']
            ];
        } catch (Exception $e) {
            return [
                'average_rating' => 0,
                'total_reviews' => 0
            ];
        }
    }
    
}
