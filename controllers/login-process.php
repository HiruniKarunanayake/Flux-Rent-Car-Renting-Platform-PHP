<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Import the necessary models
require_once '../models/VehicleDriver.php';
require_once '../models/Customer.php';
require_once '../models/VehicleOwner.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Validate input
    if (empty($email) || empty($password) || empty($role)) {
        echo "All fields are required.";
        exit;
    }

    // Login based on the role
    switch ($role) {
        case 'driver':
            $driver = new VehicleDriver();
            $loginResult = $driver->login($email, $password);
            break;
        case 'customer':
            $customer = new Customer();
            $loginResult = $customer->login($email, $password);
            break;
        case 'owner':
            $vehicleOwner = new VehicleOwner();
            $loginResult = $vehicleOwner->login($email, $password);
            break;
        default:
            echo "Invalid role selected.";
            exit;
    }

    // Check login result
    if ($loginResult) {
        // Store user details in session
        $_SESSION['user_id'] = $loginResult['id'];
        $_SESSION['role'] = $role;

        // Redirect based on the role
        switch ($role) {
            case 'driver':
                header("Location: ../driver-portal.php");
                break;
            case 'customer':
                header("Location: ../index.php");
                break;
            case 'owner':
                header("Location: ../vehicle-owner-portal.php");
                break;
        }
        exit; // Prevent further script execution
    } else {
        echo "Invalid email or password.";
    }
}
?>
