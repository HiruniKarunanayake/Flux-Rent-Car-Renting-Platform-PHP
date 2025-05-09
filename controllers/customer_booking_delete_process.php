<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

require_once '../models/Booking.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = $_POST['bookingId'];
    $userId = $_SESSION['user_id'];

    // Call the delete method from the Booking class
    $deleteResult = Booking::deleteBooking($bookingId, $userId);

    if ($deleteResult === true) {
        header('Location: ../customer-bookings.php');
    } else {
        // Handle unauthorized attempt or booking is approved
        echo "You are not authorized to delete this booking or the booking has been approved.";
    }
}
?>
