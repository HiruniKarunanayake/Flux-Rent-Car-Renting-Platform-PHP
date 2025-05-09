<?php
require_once '../models/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    $driver_id = isset($_POST['driver_id']) ? $_POST['driver_id'] : null;

    // Update booking status
    $query = "UPDATE bookings SET status = '$status'";
    
    if ($driver_id) {
        $query .= ", driver_id = $driver_id";
    }
    
    $query .= " WHERE id = $booking_id";

    $result = Database::iud($query);

    if ($result === true) {
        $_SESSION['message'] = 'Booking status updated successfully!';
    } else {
        $_SESSION['error'] = $result;
    }

    // Redirect back to the portal page
    header("Location: ../vehicle-owner-bookings.php");
    exit();
}
