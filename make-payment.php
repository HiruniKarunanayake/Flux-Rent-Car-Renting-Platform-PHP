<?php
session_start();

// Constants
define('COMMISSION_RATE', 0.05);

// Session validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$owner_id = $_SESSION['user_id'];

// Fetch total earnings for the owner from the bookings table
$earningsQuery = "SELECT SUM(b.amount) AS total_earnings
                  FROM bookings b
                  JOIN vehicles v ON b.vehicle_id = v.id
                  WHERE v.owner = '{$owner_id}' AND b.status='approved'";
$earningsResult = Database::search($earningsQuery);
$totalEarnings = 0;

if ($earningsResult && $earningsResult->num_rows > 0) {
    $earningsData = $earningsResult->fetch_assoc();
    $totalEarnings = $earningsData['total_earnings'];
}

// Calculate earnings after commission
$commission = $totalEarnings * COMMISSION_RATE;
$earningsAfterCommission = $totalEarnings - $commission;

// Fetch total payments made
$paymentsQuery = "SELECT SUM(amount) AS total_payment FROM payments WHERE owner_id = '{$owner_id}'";
$paymentsResult = Database::search($paymentsQuery);
$totalPayments = 0;

if ($paymentsResult && $paymentsResult->num_rows > 0) {
    $paymentsData = $paymentsResult->fetch_assoc();
    $totalPayments = $paymentsData['total_payment'];
}

// Calculate the total due amount
$totalDue = $commission - $totalPayments;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'sidebar.php' ?>
        </div>
        <div class="content col-10 mb-5 mt-5">
            <div class="container mt-5 col-6">
                <h2 class="mb-4">Make Payment</h2>

                <!-- Payment Summary Section -->
                <div class="card mb-4 border border-primary shadow-lg p-3 mb-5 bg-white rounded" style="background-color: #f9f9f9;">
                    <div class="card-body">
                        <h4 class="card-title text-primary"><i class="fas fa-wallet"></i> Payment Summary</h4>
                        <p class="card-text fs-5 text-dark">
                            <strong>Total Earnings: </strong>Rs <?php echo number_format($totalEarnings, 2); ?><br>
                            <strong>Earnings After 5% Commission: </strong>Rs <?php echo number_format($earningsAfterCommission, 2); ?><br>
                            <strong>Total Payments Made: </strong>Rs <?php echo number_format($totalPayments, 2); ?><br>
                            <strong>Total Due: </strong>
                            <span class="<?php echo $totalDue >= 0 ? 'text-success' : 'text-danger'; ?>">
                                Rs <?php echo number_format($totalDue, 2); ?>
                            </span>
                        </p>
                    </div>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']); // Clear error message after displaying
                        ?>
                    </div>
                <?php endif; ?>
                <!-- Payment Form -->
                <div class="card mb-4 shadow-lg p-3 mb-5 bg-white rounded">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-credit-card"></i> Enter Payment Details</h4>
                        <form action="controllers/payment_process.php" method="POST" onsubmit="return validatePaymentForm()">
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Card Number</label>
                                <input type="text" class="form-control" id="cardNumber" name="cardNumber" maxlength="16" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="expiryDate" class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY" maxlength="5" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" maxlength="3" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Payment Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $totalDue; ?>" required readonly>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Make Payment</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function validatePaymentForm() {
            const cardNumber = document.getElementById('cardNumber').value;
            const expiryDate = document.getElementById('expiryDate').value;
            const cvv = document.getElementById('cvv').value;

            // Card number validation
            const cardRegex = /^[0-9]{16}$/;
            if (!cardRegex.test(cardNumber)) {
                alert('Please enter a valid 16-digit card number.');
                return false;
            }

            // Expiry date validation (MM/YY format)
            const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
            if (!expiryRegex.test(expiryDate)) {
                alert('Please enter a valid expiry date in MM/YY format.');
                return false;
            }

            // CVV validation (3 digits)
            const cvvRegex = /^[0-9]{3}$/;
            if (!cvvRegex.test(cvv)) {
                alert('Please enter a valid 3-digit CVV.');
                return false;
            }

            return true;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>