<?php
include '../models/config.php'; 
include '../models/Inquire.php'; 

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = $_POST['id']; // ID of the inquiry being updated
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate that all required fields are filled
    if (!empty($id) && !empty($name) && !empty($email) && !empty($phone) && !empty($subject) && !empty($message)) {
        // Call the updateInquiry method in the Inquire class to update the inquiry
        $updateResult = Inquire::updateInquiry($id, $name, $email, $phone, $subject, $message);

        // Check if the update was successful
        if ($updateResult === true) {
            // Redirect with a success message
            header("Location: ../admin-manage-inquiries.php?success=Inquiry updated successfully");
            exit;
        } else {
            // Redirect with an error message if the update fails
            header("Location: ../admin-update-inquire.php?id=$id&error=Failed to update inquiry: $updateResult");
            exit;
        }
    } else {
        // Redirect back to the update form with an error message if validation fails
        header("Location: ../admin-update-inquire.php?id=$id&error=All fields are required");
        exit;
    }
} else {
    // Redirect to the inquiries management page if the request method is not POST
    header("Location: ../admin-manage-inquiries.php");
    exit;
}
?>
