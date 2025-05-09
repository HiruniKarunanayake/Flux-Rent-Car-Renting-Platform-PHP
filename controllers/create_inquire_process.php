<?php
require_once '../models/Inquire.php'; // Make sure to include the Inquire class

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    // Simple validation (you can add more detailed validation if needed)
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        // Redirect back with an error message if validation fails
        header('Location: ../contact.php?error=Please fill in all fields');
        exit();
    }

    // Create a new Inquire object and pass the form data to the constructor
    $inquiry = new Inquire($name, $email, $phone, $subject, $message);

    // Try to create a new inquiry
    $result = $inquiry->createInquire();

    if ($result === true) {
        // Redirect to success page or show a success message
        header('Location: ../contact.php?success=Inquiry submitted successfully');
        exit();
    } else {
        // Handle the error, maybe log it or show an error message
        header('Location: ../contact.php?error=Failed to submit inquiry. Please try again.');
        exit();
    }
} else {
    // If not a POST request, redirect to the contact page
    header('Location: contact.php');
    exit();
}
