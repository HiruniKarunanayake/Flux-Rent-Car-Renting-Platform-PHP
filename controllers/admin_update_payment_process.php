<?php
session_start();

// Session validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../login.php");
    exit();
}

require_once '../models/Payment.php';

// Get payment details from the form submission
$payment_id = $_POST['payment_id'];
$amount = $_POST['amount'];
$payment_date = $_POST['payment_date'];
$status = $_POST['status'];

// Validate input
if (empty($payment_id) || empty($amount) || empty($payment_date) || empty($status)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: ../admin-update-payment.php?payment_id=" . $payment_id);
    exit();
}

$result = Payment::updatePayment($payment_id, $amount, $payment_date, $status);

if ($result === true) {
    $_SESSION['success'] = "Payment updated successfully.";
    header("Location: ../admin-update-payment.php?payment_id=" . $payment_id);
} else {
    $_SESSION['error'] = $result;  
    header("Location: ../admin-update-payment.php?payment_id=" . $payment_id);
}
exit();
