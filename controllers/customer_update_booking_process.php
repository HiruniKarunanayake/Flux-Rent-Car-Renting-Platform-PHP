<?php
session_start(); 
require_once '../models/config.php'; 
require_once '../models/Booking.php'; 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

// Check if the required POST data is available
if (isset($_POST['bookingId'], $_POST['startDate'], $_POST['endDate'], $_POST['location'])) {
    // Sanitize the input data
    $bookingId = htmlspecialchars($_POST['bookingId']);
    $startDate = htmlspecialchars($_POST['startDate']);
    $endDate = htmlspecialchars($_POST['endDate']);
    $location = htmlspecialchars($_POST['location']);
    $withDriver = isset($_POST['withDriver']) ? true : false;

    $booking = new Booking();
    $currentBooking = $booking->getBookingDetails($bookingId);
    $vehicleId = $currentBooking['vehicle_id'] ?? null;

    if (!$vehicleId) {
        die("Vehicle not found for this booking.");
    }

    $status = 'pending';

    $userId = $_SESSION['user_id'];

    $updatedBooking = new Booking(
        $vehicleId, 
        $startDate,
        $endDate,
        $location,
        $withDriver,
        $userId,    
        $status
    );

    $updateResult = $updatedBooking->updateBooking($bookingId);

    if ($updateResult === true) {
        header('Location: ../booking_success.php?message=Booking updated successfully');
        exit();
    } else {
        echo "Error updating booking: " . $updateResult;
    }
} else {
    echo "Missing required fields.";
}
