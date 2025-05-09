<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

$owner_id = $_SESSION['user_id'];

// Query for bookings
$query = "SELECT b.id, b.vehicle_id, b.start_date, b.end_date, b.location, b.with_driver, b.status, v.make, v.model
          FROM bookings b
          JOIN vehicles v ON b.vehicle_id = v.id
          WHERE v.owner = $owner_id";

$result = Database::search($query);
$bookings = $result->fetch_all(MYSQLI_ASSOC); // Fetch all bookings into an array

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
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'sidebar.php' ?>
        </div>

        <div class="content  col-10 mb-5 mt-5">
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
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal-<?= $booking['id'] ?>">
                                        Update Status
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for each booking -->
            <?php foreach ($bookings as $booking): ?>
                <div class="modal fade" id="updateModal-<?= $booking['id'] ?>" tabindex="-1" aria-labelledby="updateModalLabel-<?= $booking['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="controllers/process_update_booking.php" method="POST">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateModalLabel-<?= $booking['id'] ?>">Update Booking Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <div class="mb-3">
                                        <label for="status-<?= $booking['id'] ?>" class="form-label">Status</label>
                                        <select name="status" id="status-<?= $booking['id'] ?>" class="form-control">
                                            <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $booking['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $booking['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>
                                    <?php if ($booking['with_driver']): ?>
                                        <div class="mb-3">
                                            <label for="driver-<?= $booking['id'] ?>" class="form-label">Assign Driver</label>
                                            <select name="driver_id" id="driver-<?= $booking['id'] ?>" class="form-control">
                                                <?php
                                                // Query for available drivers
                                                $start_date = $booking['start_date'];
                                                $end_date = $booking['end_date'];

                                                $availableDriversQuery = "
    SELECT vd.id, vd.first_name, vd.last_name, vd.charg_per_day
    FROM vehicle_drivers vd
    WHERE vd.id NOT IN (
        SELECT b.driver_id
        FROM bookings b
        WHERE b.with_driver = 1
        AND b.driver_id IS NOT NULL
        AND (
            (b.start_date <= '$end_date' AND b.end_date >= '$start_date') -- Overlap condition
        )
    )";

                                                $availableDriversResult = Database::search($availableDriversQuery);
                                                $availableDrivers = $availableDriversResult->fetch_all(MYSQLI_ASSOC);

                                                foreach ($availableDrivers as $driver): ?>
                                                    <option value="<?= $driver['id'] ?>"><?= htmlspecialchars($driver['first_name']) . ' ' . htmlspecialchars($driver['last_name']). ' - Rs.' . htmlspecialchars($driver['charg_per_day'].' per day') ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Booking</button>
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