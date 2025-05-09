<?php
session_start();

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
                  WHERE v.owner = '{$owner_id}' AND b.status = 'approved'";
$earningsResult = Database::search($earningsQuery);
$totalEarnings = 0;
if ($earningsResult && $earningsResult->num_rows > 0) {
    $earningsData = $earningsResult->fetch_assoc();
    $totalEarnings = $earningsData['total_earnings'];
}

// Fetch payment history for the owner from the payments table
$paymentsQuery = "SELECT * FROM payments WHERE owner_id = '{$owner_id}' ORDER BY payment_date DESC";
$paymentsResultHistory = Database::search($paymentsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Earnings and Payment History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'sidebar.php' ?>
        </div>
        <div class="content  col-10 mb-5 mt-5">
            <div class="container mt-5" style="margin-top: 20px;">
                <div class="mt-5">
                    <h2 class="mb-4 ">Total Earnings & Payment History</h2>

                    <!-- Total Earnings Section -->
                    <div class="card mb-4 border border-primary shadow-sm" style="background-color: #f9f9f9;">
                        <div class="card-body">
                            <h4 class="card-title text-primary"><i class="fas fa-wallet"></i> Total Earnings</h4>
                            <p class="card-text fs-5 text-dark">
                                <?php
                                // Calculate system's 5% commission
                                $commission = $totalEarnings * 0.05;
                                $earningsAfterCommission = $totalEarnings - $commission;

                                // Calculate total payments made
                                $paymentsQuery = "SELECT SUM(amount) AS total_payment FROM payments WHERE owner_id = '{$owner_id}'";
                                $paymentsResult = Database::search($paymentsQuery);
                                $totalPayments = 0;

                                if ($paymentsResult && $paymentsResult->num_rows > 0) {
                                    $paymentsData = $paymentsResult->fetch_assoc();
                                    $totalPayments = $paymentsData['total_payment'];
                                }

                                $totalDue = $totalEarnings-$earningsAfterCommission - $totalPayments;
                                ?>
                                <strong>Total Earnings: </strong>Rs<?php echo number_format($totalEarnings, 2); ?><br>
                                <strong>Earnings After 5% Commission: </strong>Rs <?php echo number_format($earningsAfterCommission, 2); ?><br>
                                <strong>Total Payments Made: </strong>Rs <?php echo number_format($totalPayments, 2); ?><br>
                                <strong>Total Due: </strong>
                                <span class="<?php echo $totalDue >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    Rs <?php echo number_format($totalDue, 2); ?>
                                </span>
                            </p>

                            <div class="d-flex justify-content-end">
                                <a href="make-payment.php" class="btn btn-primary">
                                    <i class="fas fa-credit-card"></i> Make Payment
                                </a>
                            </div>
                        </div>
                    </div>


                    <!-- Payment History Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-history"></i> Payment History</h4>
                            <?php if ($paymentsResultHistory && $paymentsResultHistory->num_rows > 0) : ?>
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Payment ID</th>
                                            <th>Amount</th>
                                            <th>Payment Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($payment = $paymentsResultHistory->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                                <td>Rs <?php echo number_format($payment['amount'], 2); ?></td>
                                                <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                                                <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No payment history available.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>