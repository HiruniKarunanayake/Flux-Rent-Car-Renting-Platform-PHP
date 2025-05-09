<?php
session_start();

// Session validation to ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';
require_once 'models/Payment.php';

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    // Fetch the payment details from the database
    $query = "SELECT payments.*, vehicle_owners.first_name, vehicle_owners.last_name 
              FROM payments 
              JOIN vehicles ON payments.owner_id = vehicles.owner 
              JOIN vehicle_owners ON vehicles.owner = vehicle_owners.id 
              WHERE payments.id = '{$payment_id}'";

    $paymentResult = Database::search($query);

    if ($paymentResult && $paymentResult->num_rows > 0) {
        $payment = $paymentResult->fetch_assoc();
        // Store payment details in session for use across pages
        $_SESSION['payment'] = $payment;
    } else {
        // Store error in session and redirect to the payment history page
        $_SESSION['error'] = "Payment not found";
    }
} else {
    // Store error in session and redirect if payment ID is invalid
    $_SESSION['error'] = "Invalid payment ID";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            height: 100%;
        }

        .wrapper {
            min-height: 100%;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php' ?>
        </div>

        <div class="content  col-10 mb-5 mt-5">
            <div class="container mt-5">
                <?php
                // Check if there is a success message or an error message set in the session
                if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    // Unset the success message after displaying it
                    unset($_SESSION['success']);
                endif;

                if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    // Unset the error message after displaying it
                    unset($_SESSION['error']);
                endif;
                ?>

                <h2>Update Payment</h2>
                <form action="controllers/admin_update_payment_process.php" method="POST">
                    <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($payment['id']); ?>">

                    <div class="mb-3">
                        <label for="owner_name" class="form-label">Vehicle Owner</label>
                        <input type="text" id="owner_name" class="form-control"
                            value="<?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control"
                            value="<?php echo htmlspecialchars($payment['amount']); ?>" required>
                    </div>

                    <div class="row mb-3">
                        <!-- Payment Date -->
                        <div class="col-md-6">
                            <label for="payment_date" class="form-label">Payment Date</label>
                            <input type="date" id="payment_date" name="payment_date" class="form-control"
                                value="<?php echo htmlspecialchars(substr($payment['payment_date'], 0, 10)); ?>" required>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="Paid" <?php echo ($payment['status'] === 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                <option value="Pending" <?php echo ($payment['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                            </select>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Update Payment</button>
                    <a href="admin_payment_history.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>