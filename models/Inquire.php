<?php

require_once 'config.php';

class Inquire
{
    // Private properties for the Inquire class
    private $id;
    private $name;
    private $email;
    private $phone;
    private $subject; 
    private $message;
    private $createdDate;

    // Constructor to initialize properties
    public function __construct($name = null, $email = null, $phone = null, $subject = null, $message = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->subject = $subject;
        $this->message = $message;
        $this->createdDate = date('Y-m-d H:i:s');
    }

    // Create a new inquiry
    public function createInquire()
    {
        $query = "INSERT INTO inquiries (name, email, phone, subject, message, createdDate) 
                  VALUES ('$this->name', '$this->email', '$this->phone', '$this->subject', '$this->message', '$this->createdDate')";
        return Database::iud($query);
    }

    // Fetch an inquiry by ID
    public static function inquireById($id)
    {
        $query = "SELECT * FROM inquiries WHERE id = $id";
        $result = Database::search($query);
        return $result->fetch_assoc();
    }

    // Update an inquiry by ID
    public static function updateInquiry($id, $name, $email, $phone, $subject, $message)
    {
        // Prepare the update query
        $query = "UPDATE inquiries SET 
                    name = '$name', 
                    email = '$email', 
                    phone = '$phone', 
                    subject = '$subject', 
                    message = '$message'
                  WHERE id = $id";

        return Database::iud($query);
    }

    // Delete an inquiry by ID
    public static function deleteInquiry($id)
    {
        // Prepare the delete query
        $query = "DELETE FROM inquiries WHERE id = $id";

        // Execute the delete query using the iud method in the Database class
        return Database::iud($query);
    }

    // Get all inquiries
    public static function getAllInquiries()
    {
        $query = "SELECT * FROM inquiries ORDER BY createdDate DESC";
        $result = Database::search($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
