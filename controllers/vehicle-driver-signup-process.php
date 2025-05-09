<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/VehicleDriver.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Log form data for debugging
    error_log("Form data: " . print_r($_POST, true));

    // Retrieve form data and sanitize it
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $emergencyContactName = trim($_POST['emergencyContactName'] ?? '');
    $emergencyContactPhone = trim($_POST['emergencyContactPhone'] ?? '');
    $drivingExperience = intval($_POST['drivingExperience'] ?? 0);

    // Perform basic validations
    $errors = [];
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password) || empty($dob) || empty($emergencyContactName) || empty($emergencyContactPhone) || $drivingExperience <= 0) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Log for debugging
    error_log("Validations completed, errors: " . print_r($errors, true));

    // Check if the email already exists in the database
    if (empty($errors) && VehicleDriver::emailExists($email)) {
        $errors[] = "Email is already registered.";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        $vehicleDriver = new VehicleDriver($firstName, $lastName, $email, $phone, $password, $dob, $emergencyContactName, $emergencyContactPhone, $drivingExperience);
        $registrationResult = $vehicleDriver->register();

        // Log for debugging
        error_log("Registration result: " . print_r($registrationResult, true));

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
