<?php
require_once 'models/Vehicle.php';

// Create a Vehicle object
$vehicleObj = new Vehicle();

// Check if the startDate and endDate are set in the URL parameters
if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Fetch available vehicles for the given date range
    $vehicles = $vehicleObj->getAvailableVehicles($startDate, $endDate);
} else {
    // Fetch all vehicles if no date range is provided
    $vehicles = $vehicleObj->getAllVehicles();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/car_booking1.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: black;
            position: relative;
        }

        /* Add a blurred overlay over the background image */
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

        .car-wrap {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            background-color: rgba(34, 34, 34, 0.85); 
        }

        .car-wrap:hover {
            transform: scale(1.05);
        }

        .img {
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .text {
            padding: 15px;
        }

        .price {
            font-weight: bold;
            color: #ff5f40;
        }

        .cat {
            font-style: italic;
        }
    </style>
</head>

<body style="background-color: #f8f9fa; ">

    <?php include 'header.php'; ?>

    <!-- Car Cards Section -->
    <div class="container mt-5 pt-5" >
        <div class="row mt-5">
            <!-- Loop through the vehicles and display each one -->
            <?php if (empty($vehicles)): ?>
                <p>No vehicles are available for the selected dates.</p>
            <?php else: ?>
                <?php
                if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
                    $startDate = $_GET['startDate'];
                    $endDate = $_GET['endDate'];
                ?>
                    <h3>Available vehicles for <?php echo htmlspecialchars($startDate); ?> - <?php echo htmlspecialchars($endDate); ?>.</h3>
                <?php
                }
                ?>
                <?php foreach ($vehicles as $vehicle): ?>
                    <div class="col-md-4 mb-4 rounded">
                        <div class="car-wrap rounded bg-light">
                            <!-- Use the first image if available, otherwise show a default image -->
                            <div class="img" style="background-image: url('controllers/<?php echo !empty($vehicle['images']) ? $vehicle['images'][0] : 'default-image.jpg'; ?>');"></div>
                            <div class="text">
                                <h2 class="mb-0">
                                    <?php echo htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']); ?>
                                </h2>
                                <div class="d-flex mb-3">
                                    <span class="cat"><?php echo htmlspecialchars($vehicle['make']); ?></span>
                                    <p class="price ms-auto">Rs <?php echo htmlspecialchars($vehicle['price']); ?> <span>/day</span></p>
                                </div>
                                <p class="d-flex mb-0 d-block">
                                    <a href="book-now.php?vehicleId=<?php echo $vehicle['id'] ?>" class="btn btn-primary py-2 me-1 col-12">Book now</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>