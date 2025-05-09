<?php
session_start();
require_once '../models/Booking.php';

// Check if user is logged in and has the role of 'customer'
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

// Get userId and role from session
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Check if userId is valid
if (!$userId || !is_numeric($userId)) {
    header("Location: ../login.php");
    exit();
}

// Get booking data from form submission
$vehicleId = isset($_POST['vehicleId']) ? $_POST['vehicleId'] : null;
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;
$location = isset($_POST['location']) ? $_POST['location'] : null;
$withDriver = isset($_POST['withDriver']) ? true : false;

// Validate form inputs
if (empty($vehicleId) || empty($startDate) || empty($endDate) || empty($location)) {
    echo "Error: Missing required fields.";
    exit();
}

// Create a new Booking object
$booking = new Booking($vehicleId, $startDate, $endDate, $location, $withDriver, $userId, 'pending',$amount);

// Add the booking to the database
$result = $booking->addBooking();

if ($result === true) {
    header("Location: ../booking_success.php");
} else {
    // Display error message if the booking fails
    echo $result;
}
?>
