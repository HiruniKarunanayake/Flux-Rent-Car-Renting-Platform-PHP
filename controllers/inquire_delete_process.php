<?php
// Include necessary files
include '../models/config.php';
include '../models/Inquire.php'; 

// Check if an ID is provided
if (isset($_GET['id'])) {
    $inquiryId = $_GET['id'];

    // Call the deleteInquiry method to delete the inquiry
    $deleteResult = Inquire::deleteInquiry($inquiryId);

    // Check if the deletion was successful
    if ($deleteResult === true) {
        // Redirect with a success message
        header("Location: ../admin-manage-inquiries.php?success=Inquiry deleted successfully");
        exit;
    } else {
        // Redirect with an error message if deletion fails
        header("Location: ../admin-manage-inquiries.php?error=Failed to delete inquiry: $deleteResult");
        exit;
    }
} else {
    // Redirect if no ID is provided
    header("Location: ../admin-manage-inquiries.php?error=No inquiry ID provided");
    exit;
}
?>
