<?php
session_start();
require_once '../models/config.php';
require_once '../models/Customer.php';
require_once '../models/VehicleOwner.php';
require_once '../models/VehicleDriver.php';
require_once '../models/Booking.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Function to check if the user is part of any approved booking
function hasApprovedBooking($userId, $role) {
    $bookings = Booking::getBookingsByUserIdAndRole($userId, $role);
    
    foreach ($bookings as $booking) {
        if ($booking['status'] === 'approved') {
            return true; // User has at least one approved booking
        }
    }
    return false; // No approved bookings
}

// If the user confirms deletion and no approved booking exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        if (hasApprovedBooking($userId, $role)) {
            $_SESSION['error'] = "Account cannot be deleted because you have an approved booking.";
            header('Location: ../profile.php');
            exit();
        } else {
            // Proceed to delete the user's account based on their role
            switch ($role) {
                case 'customer':
                    Customer::deleteAccount($userId);
                    break;
                case 'vehicle_driver':
                    VehicleDriver::deleteAccount($userId);
                    break;
                case 'vehicle_owner':
                    VehicleOwner::deleteAccount($userId);
                    break;
                default:
                    header('Location: ../login.php');
                    exit();
            }

            // Destroy session after deletion
            session_destroy();
            header('Location: ../login.php'); // Redirect to a goodbye or logout page
            exit();
        }
    }
} else {
    header('Location: ../profile.php'); 
    exit();
}
?>
