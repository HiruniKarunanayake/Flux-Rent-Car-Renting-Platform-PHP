<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['booking_id']) && is_numeric($_POST['booking_id'])) {
        $booking_id = intval($_POST['booking_id']);
        
        try {
            // Prepare and execute the delete query
            $query = "DELETE FROM bookings WHERE id = ?";
            
            // Connect to database and prepare statement
            Database::setUpConnection();
            $pstmt = $connection->prepare($query);
            
            if (!$pstmt) {
                throw new Exception("Failed to prepare statement: " . $connection->error);
            }
            
            // Bind parameter
            $pstmt->bind_param('i', $booking_id);
            
            // Execute the statement
            if ($pstmt->execute()) {
                // Close the statement
                $pstmt->close();
                
                // Redirect with a success message
                header("Location: admin_bookings.php?message=deleted");
                exit();
            } else {
                throw new Exception("Error executing statement: " . $pstmt->error);
            }
        } catch (Exception $e) {
            // Log the error and show user-friendly message
            error_log($e->getMessage());
            header("Location: admin_bookings.php?error=delete_failed");
            exit();
        }
    } else {
        header("Location: admin_bookings.php?error=invalid_id");
        exit();
    }
}

// If we get here, it wasn't a POST request
header("Location: admin_bookings.php");
exit();
?>