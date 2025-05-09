<?php
session_start();
require_once '../models/config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Import the necessary models
require_once '../models/VehicleDriver.php';
require_once '../models/Customer.php';
require_once '../models/VehicleOwner.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate input
    if (empty($email) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Prepare the SQL statement to find the customer
    $query = "SELECT * FROM admin WHERE email='$email ' AND password='$password'";

    $result = Database::search($query);

    if ($result->num_rows ==1) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['user_id'] = $adminData['id'];
        $_SESSION['role'] = 'admin';


        header("Location: ../admin-portal.php");
        exit();
    } else {
        echo "Invalid email or password.";
        exit();      
    }
}
