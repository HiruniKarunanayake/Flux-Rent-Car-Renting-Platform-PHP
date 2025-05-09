<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'driver') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$driver_id = $_SESSION['user_id'];

// Fetch bookings along with vehicle make and model
$totalBookingsQuery = "
    SELECT b.*, v.make, v.model 
    FROM bookings b
    JOIN vehicles v ON b.vehicle_id = v.id
    WHERE b.driver_id = $driver_id";

$totalBookingsResult = Database::search($totalBookingsQuery);
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
            <?php include 'driver_sidebar.php' ?>
        </div>

        <div class="content col-10 mb-5 mt-5">
            <div class="container my-5">
                <h2 class="mb-4 text-center">Driver Bookings</h2>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Vehicle Make</th>
                            <th>Vehicle Model</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Location</th>
                            
                        </tr>
                    </thead>
                    <tbody id="driverTableBody">
                        <?php if ($totalBookingsResult && $totalBookingsResult->num_rows > 0) : ?>
                            <?php while ($Booking = $totalBookingsResult->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($Booking['make']); ?></td>
                                    <td><?php echo htmlspecialchars($Booking['model']); ?></td>
                                    <td><?php echo htmlspecialchars($Booking['start_date']); ?></td>
                                    <td><?php echo htmlspecialchars($Booking['end_date']); ?></td>
                                    <td><?php echo htmlspecialchars($Booking['location']); ?></td>
                                   
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">No bookings found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
