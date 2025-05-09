<?php

require_once 'config.php';

class Payment
{
    public static function createPayment($owner_id, $amount, $payment_date, $status)
    {
        // Validate payment details
        if (empty($owner_id) || empty($amount) || empty($payment_date)) {
            return "All fields are required.";
        }

        // Insert payment into the payments table
        $query = "INSERT INTO payments (owner_id, amount, payment_date, status) 
                  VALUES ('{$owner_id}', '{$amount}', '{$payment_date}', '{$status}')";

        // Use the iud method from the Database class to execute the query
        return Database::iud($query);
    }
    public static function deletePayment($payment_id)
    {
        if (empty($payment_id)) {
            return "Payment ID is required.";
        }

        // Delete query
        $query = "DELETE FROM payments WHERE id = '{$payment_id}'";

        // Use the iud method from the Database class to execute the query
        return Database::iud($query);
    }

    public static function updatePayment($payment_id, $amount, $payment_date, $status)
    {
        // Validate payment details
        if (empty($payment_id) || empty($amount) || empty($payment_date) || empty($status)) {
            return "All fields are required.";
        }

        // Update payment query
        $query = "UPDATE payments 
                  SET amount = '{$amount}', payment_date = '{$payment_date}', status = '{$status}' 
                  WHERE id = '{$payment_id}'";

        // Use the iud method from the Database class to execute the query
        return Database::iud($query);
    }
}
