<?php
session_start();
require_once '../models/Customer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $vehicleId = isset($_POST['vehicleId']) ? intval($_POST['vehicleId']) : 0;
    $customerName = isset($_POST['customerName']) ? trim($_POST['customerName']) : '';
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    // Validate input
    if ($vehicleId <= 0 || empty($customerName) || empty($comment) || $rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Please fill in all required fields correctly.";
        header("Location: ../book-now.php?vehicleId=" . $vehicleId);
        exit();
    }

    $customer = new Customer();
    
    // Add the review
    $result = $customer->addReview($vehicleId, $customerName, $comment, $rating);

    if ($result === true) {
        $_SESSION['success'] = "Review submitted successfully!";
    } else {
        $_SESSION['error'] = "Failed to submit review: " . $result;
    }

    // Redirect back to the vehicle page
    header("Location: ../book-now.php?vehicleId=" . $vehicleId);
    exit();
} else {
    // If not POST request, redirect to home
    header("Location: ../index.php");
    exit();
}
?>