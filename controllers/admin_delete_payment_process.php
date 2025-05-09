<?php
require_once '../models/Payment.php';

if (isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    $result = Payment::deletePayment($payment_id);

    if ($result) {
        header("Location: ../admin-manage-payments.php");
    } else {
        header("Location: ../admin-manage-payments.php");
    }
} else {
    header("Location: ../admin-manage-payments.php");
}
?>
