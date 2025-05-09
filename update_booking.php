<?php
// Include your Booking model
require_once 'models/Booking.php';

// Get the booking ID from the query parameter
$bookingId = isset($_GET['bookingId']) ? intval($_GET['bookingId']) : 0;

$bookingModel = new Booking();
// Validate booking ID and fetch booking details
if ($bookingId > 0) {
    $booking = $bookingModel->getBookingDetails($bookingId);
    if (!$booking) {
        // If no booking is found with the given ID, show an error
        $errorMessage = "No booking found with this ID.";
    }
} else {
    $errorMessage = "Invalid booking ID.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
    </style>
</head>

<body style="background-color: #f8f9fa;">
    <?php include 'header.php'; ?>

    <div class="container centered-container mt-5">
        <div class="row justify-content-center pt-5">
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($errorMessage) ?>
                </div>
            <?php else: ?>
                <!-- Update Booking Form Section -->
                <div class="container d-flex justify-content-center align-items-center mt-5">
                    <div class="form-section col-12 col-md-6">
                        <h3 class="text-center mb-4">Update Booking</h3>
                        <form id="updateBookingForm" method="POST" action="controllers/customer_update_booking_process.php">
                            <input type="hidden" name="bookingId" value="<?= htmlspecialchars($booking['id']) ?>">

                            <!-- Vehicle Information -->
                            <div class="mb-3">
                                <label class="form-label">Vehicle Make</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($booking['vehicle_make']) ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vehicle Model</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($booking['vehicle_model']) ?>" disabled>
                            </div>

                            <!-- Start Date -->
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" value="<?= htmlspecialchars($booking['start_date']) ?>" required>
                            </div>

                            <!-- End Date -->
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" value="<?= htmlspecialchars($booking['end_date']) ?>" required>
                            </div>

                            <!-- Pickup Location -->
                            <div class="mb-3">
                                <label for="location" class="form-label">Pickup Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($booking['location']) ?>" required>
                            </div>

                            <!-- With Driver Option -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="withDriver" name="withDriver" <?= $booking['with_driver'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="withDriver">Get with a Driver</label>
                            </div>

                            <!-- Update Button -->
                            <button type="button" class="btn btn-primary w-100" onclick="validateForm()">Update Booking</button>
                        </form>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Confirm Booking Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update this booking?</p>
                    <ul id="bookingSummary"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        function validateForm() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const location = document.getElementById('location').value;
            const withDriver = document.getElementById('withDriver').checked;

            if (!startDate || !endDate || !location) {
                alert("Please fill out all required fields.");
                return;
            }

            if (new Date(endDate) < new Date(startDate)) {
                alert("End date cannot be before the start date.");
                return;
            }

            const summary = `
            <li><strong>Start Date:</strong> ${startDate}</li>
            <li><strong>End Date:</strong> ${endDate}</li>
            <li><strong>Pickup Location:</strong> ${location}</li>
            <li><strong>With Driver:</strong> ${withDriver ? "Yes" : "No"}</li>
        `;
            document.getElementById('bookingSummary').innerHTML = summary;

            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            bookingModal.show();
        }

        function submitForm() {
            document.getElementById('updateBookingForm').submit();
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>