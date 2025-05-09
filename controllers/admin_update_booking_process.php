<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../models/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];
    $with_driver = $_POST['with_driver'];
    $status = $_POST['status'];
    $amount = $_POST['amount'];

    // Update query
    $updateQuery = "UPDATE bookings 
                    SET start_date = '$start_date', end_date = '$end_date', location = '$location', 
                        with_driver = $with_driver, status = '$status', amount = $amount
                    WHERE id = $booking_id";

    // Execute the query using the iud method
    $result = Database::iud($updateQuery);

    // Check if the update was successful
    if ($result === true) {
        $_SESSION['message'] = "Booking updated successfully.";
        header("Location: ../admin-manage-bookings.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating booking: " . $result;
        header("Location: ../admin_update_booking.php?booking_id=$booking_id");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../admin-manage-bookings.php");
    exit();
}
?>
