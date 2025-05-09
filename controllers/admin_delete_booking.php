<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the booking ID from the POST request
    $bookingId = $_POST['booking_id'];

    // SQL query to delete the booking
    $query = "DELETE FROM bookings WHERE id = $bookingId";

    // Execute the deletion query using the iud method
    $result = Database::iud($query);

    // Check if the deletion was successful
    if ($result === true) {
        // Redirect back to the booking management page with a success message
        $_SESSION['message'] = "Booking deleted successfully.";
        header("Location: admin-manage-bookings.php");
        exit();
    } else {
        // Redirect back with an error message
        $_SESSION['error'] = "Error deleting booking: " . $result;
        header("Location: admin-manage-bookings.php");
        exit();
    }
} else {
    // If not a POST request, redirect to booking management page
    header("Location: admin-manage-bookings.php");
    exit();
}
?>
