<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            background-color: #666869;
        }
        .message-container {
            margin-top: 100px;
            text-align: center;
        }
        .icon {
            font-size: 50px;
            color: #4CAF50; /* Green color for the icon */
        }
    </style>
</head>
<body>
<?php include 'header.php'?>
<div class="container message-container"style="min-height:50vh">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <i class="fas fa-check-circle icon"></i>
                    <h3 class="mt-3">Booking Successful!</h3>
                    <p>Your booking has been successfully created. Please check the status of your booking later on your <a href="customer-bookings.php">My Bookings</a> page.</p>
                    <a href="index.php" class="btn btn-primary">Go to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
