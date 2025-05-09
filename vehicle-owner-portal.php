<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$owner_id = $_SESSION['user_id'];



// Fetch total bookings
$totalBookingsQuery = "SELECT COUNT(*) AS total_bookings FROM bookings WHERE vehicle_id IN (SELECT id FROM vehicles WHERE owner = $owner_id)";
$totalBookingsResult = Database::search($totalBookingsQuery);
$totalBookings = $totalBookingsResult->fetch_assoc()['total_bookings'];

// Fetch total earnings (owner's share before platform charges)
$totalEarningsQuery = "SELECT SUM(amount) AS total_earnings FROM bookings WHERE status = 'approved' AND vehicle_id IN (SELECT id FROM vehicles WHERE owner = $owner_id)";
$totalEarningsResult = Database::search($totalEarningsQuery);
$totalEarnings = $totalEarningsResult->fetch_assoc()['total_earnings'] ?? 0;

// Calculate earnings after 5% platform charge
$platformCharge = 0.05;
$earningsAfterCharge = $totalEarnings - ($totalEarnings * $platformCharge);

// Fetch booking statuses
$pendingBookingsQuery = "SELECT COUNT(*) AS pending_bookings FROM bookings WHERE status = 'pending' AND vehicle_id IN (SELECT id FROM vehicles WHERE owner = $owner_id)";
$approvedBookingsQuery = "SELECT COUNT(*) AS approved_bookings FROM bookings WHERE status = 'approved' AND vehicle_id IN (SELECT id FROM vehicles WHERE owner = $owner_id)";
$rejectedBookingsQuery = "SELECT COUNT(*) AS rejected_bookings FROM bookings WHERE status = 'rejected' AND vehicle_id IN (SELECT id FROM vehicles WHERE owner = $owner_id)";

$pendingBookings = Database::search($pendingBookingsQuery)->fetch_assoc()['pending_bookings'];
$approvedBookings = Database::search($approvedBookingsQuery)->fetch_assoc()['approved_bookings'];
$rejectedBookings = Database::search($rejectedBookingsQuery)->fetch_assoc()['rejected_bookings'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Owner | Dashboard</title>
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
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'sidebar.php' ?>
        </div>

        <div class="content  col-10 mb-5 mt-5">
            <div class="container my-5">
                <h2 class="mb-4 text-center">Owner Dashboard</h2>
                <div class="row g-4">

                    <!-- Total Earnings Card -->
                    <div class="col-md-6">
                        <div class="card dashboard-card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Total Earnings</h5>
                                <p class="card-text">Rs <?= number_format($earningsAfterCharge, 2) ?></p>
                                <p class="small">Total before charges: Rs <?= number_format($totalEarnings, 2) ?></p>
                                <a href="vehicle-owner-payment.php" class="btn btn-light mt-3">Go to Payment</a>
                            </div>
                        </div>
                    </div>

                    <!-- Other Cards -->
                    <div class="col-md-6">
                        <div class="row g-4">
                            <!-- Total Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-calendar-check"></i> Total Bookings</h5>
                                        <p class="card-text"><?= $totalBookings ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-hourglass-half"></i> Pending Bookings</h5>
                                        <p class="card-text"><?= $pendingBookings ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Approved Bookings</h5>
                                        <p class="card-text"><?= $approvedBookings ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejected Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-times-circle"></i> Rejected Bookings</h5>
                                        <p class="card-text"><?= $rejectedBookings ?></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            
        </div>

    </div>
    <?php include 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>