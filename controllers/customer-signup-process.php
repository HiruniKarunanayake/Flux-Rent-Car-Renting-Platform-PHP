<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Import the Customer model
require_once '../models/Customer.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data and sanitize it
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $preferredContactMethod = trim($_POST['preferredContactMethod'] ?? '');

    // Perform basic validations
    $errors = [];
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password) || empty($dob) || empty($preferredContactMethod)) {
        $errors[] = "All fields are required.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Check if the email already exists in the database
    if (empty($errors) && Customer::emailExists($email)) {
        $errors[] = "Email is already registered.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        $customer = new Customer($firstName, $lastName, $email, $phone, $password, $dob, $preferredContactMethod);
        $registrationResult = $customer->register();

        if ($registrationResult === true) {
            // Redirect to the login page
            header("Location: ../login.php");
            exit(); 
        } else {
            echo "Registration failed: " . $registrationResult;
        }
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
    }
}
?>
