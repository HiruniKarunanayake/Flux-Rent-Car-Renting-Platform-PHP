<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

// Query for bookings
$query = "SELECT b.id, b.vehicle_id, b.start_date, b.end_date, b.location, b.with_driver, b.status, v.make, v.model
          FROM bookings b
          JOIN vehicles v ON b.vehicle_id = v.id";
$result = Database::search($query);
$bookings = $result->fetch_all(MYSQLI_ASSOC); // Fetch all bookings into an array

// Query for available drivers (only needed once)
$driverQuery = "SELECT id, first_name, last_name FROM vehicle_drivers";
$driverResult = Database::search($driverQuery);
$drivers = $driverResult->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
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

        <div class="content col-10 mb-5 mt-5">
            <div class="container mt-4 mb-5 mt-5">
                <h2>Your Bookings</h2>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Vehicle</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Location</th>
                            <th>With Driver</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['make'] . ' ' . $booking['model']) ?></td>
                                <td><?= htmlspecialchars($booking['start_date']) ?></td>
                                <td><?= htmlspecialchars($booking['end_date']) ?></td>
                                <td><?= htmlspecialchars($booking['location']) ?></td>
                                <td><?= $booking['with_driver'] ? 'Yes' : 'No' ?></td>
                                <td><?= htmlspecialchars($booking['status']) ?></td>
                                <td>
                                    <!-- Update Button -->
                                    <a href="admin_update_booking.php?booking_id=<?= $booking['id'] ?>" class="btn btn-primary btn-sm">Update</a>

                                    <!-- Delete Button -->
                                    <!-- <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-<?= $booking['id'] ?>">Delete</button> -->
                                    <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted'): ?>
                                        <div class="alert alert-success">
                                            Booking successfully deleted.
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($_GET['error'])): ?>
                                        <div class="alert alert-danger">
                                            <?php
                                            switch ($_GET['error']) {
                                                case 'delete_failed':
                                                    echo "Failed to delete booking. Please try again.";
                                                    break;
                                                case 'invalid_id':
                                                    echo "Invalid booking ID provided.";
                                                    break;
                                                default:
                                                    echo "An error occurred.";
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modals for each booking -->
            <?php foreach ($bookings as $booking): ?>
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal-<?= $booking['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?= $booking['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="admin_delete_booking.php" method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel-<?= $booking['id'] ?>">Delete Booking</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this booking?</p>
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>