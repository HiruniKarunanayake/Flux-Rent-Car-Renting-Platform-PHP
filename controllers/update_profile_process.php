<?php
session_start();
include_once '../models/Customer.php';
include_once '../models/VehicleOwner.php';
include_once '../models/VehicleDriver.php';
include_once '../models/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Get user data from form
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Initialize an empty array for potential errors
$errors = [];

// Get posted data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$dob = $_POST['dob'] ?? '';

if ($role === 'customer') {
    $preferredContactMethod = $_POST['preferredContactMethod'] ?? '';

    // Validate and update customer profile
    $customer = new Customer();
    $result = $customer->updateProfile($userId, $firstName, $lastName, $email, $phone, $password, $dob, $preferredContactMethod);

} elseif ($role === 'owner') {
    // Validate and update vehicle owner profile
    $vehicleOwner = new VehicleOwner();
    $result = $vehicleOwner->updateProfile($userId, $firstName, $lastName, $email, $phone, $password, $dob);

} elseif ($role === 'driver') {
    $emergencyContactName = $_POST['emergency_contact_name'] ?? '';
    $emergencyContactPhone = $_POST['emergency_contact_phone'] ?? '';
    $drivingExperience = $_POST['driving_experience'] ?? '';

    // Validate and update vehicle driver profile
    $vehicleDriver = new VehicleDriver();
    $result = $vehicleDriver->updateProfile($userId, $firstName, $lastName, $email, $phone, $password, $dob, $emergencyContactName, $emergencyContactPhone, $drivingExperience);

} else {
    // Invalid role or session data
    $errors[] = "Invalid role or session data.";
}

// Redirect back to profile page
if ($result) {
    $_SESSION['success'] = "Profile updated successfully.";
    header("Location: ../profile.php");
} else {
    $_SESSION['error'] = "Failed to update profile.";
    header("Location: ../profile.php");
}
?>
