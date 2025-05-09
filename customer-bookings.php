<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}
require_once 'models/Booking.php';

// Fetch bookings for the customer
$customerId = $_SESSION['user_id'];
$bookings = Booking::getCustomerBookings($customerId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Bookings</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/car_booking1.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(5px); 
            z-index: -1;
        }

        .card {
            background-color: rgba(195, 195, 195, 0.8);
            border: none;
            margin-top: 50px;
        }

        .card-body {
            padding: 30px;
        }

        .container {
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .bookings-container {
            min-height: 80vh;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    
    <div class="container mt-5 pt-5 bookings-container">
        <h2 class="text-center" style="font-size: 50px; font-weight:bold;">My Bookings</h2>
        <div class="row">
            <?php if (empty($bookings)): ?>
                <p class="text-center">No bookings available.</p>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Booking ID: <?php echo htmlspecialchars($booking['id']); ?></h5>
                                <p class="card-text">
                                    <strong>Vehicle:</strong> <?php echo htmlspecialchars($booking['make'] . ' ' . $booking['model']); ?><br>
                                    <strong>Booking Dates:</strong> <?php echo htmlspecialchars($booking['start_date'] . ' - ' . $booking['end_date']); ?><br>
                                    <strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?><br>
                                    <strong>Pickup Location:</strong> <?php echo htmlspecialchars($booking['location']); ?>
                                </p>
                                <a href="update_booking.php?bookingId=<?php echo $booking['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Update
                                </a>
                                <?php if ($booking['status'] === 'approved'): ?>
                                    <button class="btn btn-danger" disabled data-bs-toggle="tooltip" title="Approved bookings cannot be deleted">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-booking-id="<?php echo $booking['id']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade text-dark" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this booking?
                </div>
                <div class="modal-footer">
                    <form method="POST" action="controllers/customer_booking_delete_process.php">
                        <input type="hidden" name="bookingId" id="bookingIdToDelete">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Pass booking ID to modal for deletion
        document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(button => {
            button.addEventListener('click', function () {
                var bookingId = this.getAttribute('data-booking-id');
                document.getElementById('bookingIdToDelete').value = bookingId;
            });
        });
    </script>

</body>

</html>
