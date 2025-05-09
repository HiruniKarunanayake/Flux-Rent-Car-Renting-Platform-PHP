<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

// Check if the booking ID is provided
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch current booking details from the database
    $query = "SELECT * FROM bookings WHERE id = $booking_id";
    $result = Database::search($query);

    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc(); // Fetch booking details
    } else {
        $_SESSION['error'] = "Booking not found.";
        header("Location: admin-manage-bookings.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid booking ID.";
    header("Location: admin-manage-bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php' ?>
        </div>

        <div class="content col-10 mb-5 mt-5">

            <div class="container mt-5">
                <h2>Update Booking</h2>

                <!-- Show success/error messages -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
                    <?php unset($_SESSION['message']); ?>
                <?php elseif (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="controllers/admin_update_booking_process.php" method="POST">
                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['id']) ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-control"
                                value="<?= htmlspecialchars(explode(' ', $booking['start_date'])[0]) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-control"
                                value="<?= htmlspecialchars(explode(' ', $booking['end_date'])[0]) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" id="location" name="location" class="form-control"
                                value="<?= htmlspecialchars($booking['location']) ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="with_driver" class="form-label">With Driver</label>
                            <select id="with_driver" name="with_driver" class="form-control" required>
                                <option value="1" <?= $booking['with_driver'] ? 'selected' : '' ?>>Yes</option>
                                <option value="0" <?= !$booking['with_driver'] ? 'selected' : '' ?>>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $booking['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $booking['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" id="amount" name="amount" class="form-control"
                                value="<?= htmlspecialchars($booking['amount']) ?>" required>
                        </div>
                    </div>

                    <!-- Vehicle ID and Driver ID cannot be updated -->
                    <p><strong>Note:</strong> Vehicle ID and Driver ID cannot be updated.</p>

                    <button type="submit" class="btn btn-primary">Update Booking</button>
                    <a href="admin-booking-management.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>