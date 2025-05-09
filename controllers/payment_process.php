<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}
require_once '../models/Payment.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id = $_SESSION['user_id'];
    // Validate card details
    $cardNumber = $_POST['cardNumber'];
    $expiryDate = $_POST['expiryDate'];
    $cvv = $_POST['cvv'];
    $amount = $_POST['amount'];

    $errors = [];

    // Basic card validation
    if (empty($cardNumber) || !preg_match('/^[0-9]{16}$/', $cardNumber)) {
        $errors[] = "Invalid card number.";
    }
    if (empty($expiryDate) || !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiryDate)) {
        $errors[] = "Invalid expiry date. Format should be MM/YY.";
    }
    if (empty($cvv) || !preg_match('/^[0-9]{3}$/', $cvv)) {
        $errors[] = "Invalid CVV. It should be a 3-digit number.";
    }
    if (empty($amount) || !is_numeric($amount) || $amount <= 0 ) {
        $errors[] = "Invalid amount. It should be a positive number.";
    }

    if (empty($errors)) {
        // Process payment (assuming payment processing is successful)
        $paymentDate = date("Y-m-d H:i:s");
        $status = 'Paid';

        $owner_id = $_SESSION['user_id'];

        // Insert payment into the database using Payment model
        $result = Payment::createPayment($owner_id, $amount, $paymentDate, $status);

        if ($result === true) {
            $_SESSION['success'] = "Payment processed successfully.";
        } else {
            $_SESSION['error'] = "Payment failed: " . $result;
        }

        // Redirect to payment page
        header("Location: ../vehicle-owner-payment.php");
        exit();
    } else {
        // Store errors in the session
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: ../vehicle-owner-payment.php");
        exit();
    }
}
?>
