<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$owner_id = $_SESSION['user_id'];



// Fetch total bookings
$totalBookingsQuery = "SELECT COUNT(*) AS total_bookings FROM bookings";
$totalBookingsResult = Database::search($totalBookingsQuery);
$totalBookings = $totalBookingsResult->fetch_assoc()['total_bookings'];

// Fetch total earnings (owner's share before platform charges)
$totalEarningsQuery = "SELECT SUM(amount) AS total_earnings FROM payments WHERE status = 'Paid'";
$totalEarningsResult = Database::search($totalEarningsQuery);
$totalEarnings = $totalEarningsResult->fetch_assoc()['total_earnings'] ?? 0;

// Calculate earnings after 5% platform charge
$platformCharge = 0.05;
$earningsAfterCharge = $totalEarnings - ($totalEarnings * $platformCharge);

// Fetch booking statuses
$totalCustomersQuery = "SELECT COUNT(*) AS total_customers FROM customers";
$totalDriversQuery = "SELECT COUNT(*) AS total_drivers FROM vehicle_drivers ";
$totalOwnersQuery = "SELECT COUNT(*) AS total_owners FROM vehicle_owners";

$totalCustomers = Database::search($totalCustomersQuery)->fetch_assoc()['total_customers'];
$totalDrivers = Database::search($totalDriversQuery)->fetch_assoc()['total_drivers'];
$totalOwners = Database::search($totalOwnersQuery)->fetch_assoc()['total_owners'];

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
    
    <?php
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="images/logo.png" alt="Logo" width="140" height="40" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <?php
                if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-portal.php">Portal</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="controllers/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-login.php">Login</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php' ?>
        </div>

        <div class="content  col-10 mb-5 mt-5">
            <div class="container my-3">
                <h2 class="mb-4 text-center">Admin Dashboard</h2>
                <div class="row g-4">

                    <!-- Total Earnings Card -->
                    <div class="col-md-6">
                        <div class="card dashboard-card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Total Earnings</h5>
                                <p class="card-text">Rs <?= number_format($totalEarnings, 2) ?></p>
                                <a href="admin-manage-payments.php" class="btn btn-light mt-3">Go to Payment</a>
                            </div>
                        </div>
                    </div>

                    <!-- Other Cards -->
                    <div class="col-md-6">
                        <div class="row g-4">
                            <!-- Total Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-calendar-check"></i> Total Bookings</h5>
                                        <p class="card-text"><?= $totalBookings ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-hourglass-half"></i> Total Customers</h5>
                                        <p class="card-text"><?= $totalCustomers ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-check-circle"></i> Total Vehicle Owners</h5>
                                        <p class="card-text"><?= $totalOwners ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejected Bookings Card -->
                            <div class="col-md-6">
                                <div class="card dashboard-card text-white bg-primary">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-times-circle"></i> Total Vehicle Drivers</h5>
                                        <p class="card-text"><?= $totalDrivers ?></p>
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