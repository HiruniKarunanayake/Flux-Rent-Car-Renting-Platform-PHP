<?php
session_start();
require_once 'models/Vehicle.php';
require_once 'models/Customer.php';

// Get the vehicle ID from the query parameter
$vehicleId = isset($_GET['vehicleId']) ? intval($_GET['vehicleId']) : 0;

$vehicleModel = new Vehicle();
$review = new Customer();

// Validate vehicle ID and fetch vehicle details
if ($vehicleId > 0) {
    $vehicle = $vehicleModel->getVehicleWithImages($vehicleId);
    if (!$vehicle) {
        // If no vehicle is found with the given ID, show an error
        $errorMessage = "No vehicle available with this ID.";
    } else {
        // Fetch reviews for the vehicle
        $reviews = $review->getVehicleReviews($vehicle['id']);
    }
} else {
    $errorMessage = "Invalid vehicle ID.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now - Car Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .car-details img {
            width: 100%;
            border-radius: 5px;
        }

        .car-info {
            padding: 20px;
        }

        .car-info h2 {
            font-size: 28px;
        }

        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .centered-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        #vehicleCarousel {
            max-width: 100%;
            height: auto;
        }

        #vehicleCarousel .carousel-inner {
            height: 400px;
            background-color: #212529;
            border-radius: 10px;
        }

        #vehicleCarousel .carousel-item {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #212529;
            border-radius: 10px;
        }

        #vehicleCarousel .carousel-item img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        /* Review styles */
        .star-rating {
            color: #ffd700;
        }

        .star-empty {
            color: #ccc;
        }

        .review-box {
            background-color: #fff;
            transition: transform 0.2s;
        }

        .review-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .rating-select {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating-select input {
            display: none;
        }

        .rating-select label {
            cursor: pointer;
            font-size: 1.5em;
            color: #ccc;
            padding: 0 0.1em;
        }

        .rating-select label:hover,
        .rating-select label:hover~label,
        .rating-select input:checked~label {
            color: #ffd700;
        }
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
                <!-- Display success/error messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Car Details Section -->
                <div class="row">
                    <h2><?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="car-details">
                                <!-- Carousel for Vehicle Images -->
                                <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                                    <div class="carousel-inner">
                                        <?php if (!empty($vehicle['images'])): ?>
                                            <?php foreach ($vehicle['images'] as $index => $image): ?>
                                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                    <!-- Ensure each image is displayed properly -->
                                                    <img src="controllers/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?>" class="d-block w-100" style="object-fit: cover; max-height: 400px;">
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="carousel-item active">
                                                <!-- Default fallback image if no images are available -->
                                                <img src="path/to/default/image.jpg" alt="Default Vehicle Image" class="d-block w-100" style="object-fit: cover; max-height: 400px;">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Carousel Controls -->
                                    <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- Booking Form Section -->
                        <div class="col-md-6">
                            <div class="car-info mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-car"></i> Brand: <?= htmlspecialchars($vehicle['make']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-calendar-alt"></i> Year: <?= htmlspecialchars($vehicle['year']) ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-car-side"></i> Model: <?= htmlspecialchars($vehicle['model']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-car-alt"></i> Type: <?= htmlspecialchars($vehicle['type']) ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-gas-pump"></i> Fuel Type: <?= htmlspecialchars($vehicle['fuel_type']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-cogs"></i> Transmission: <?= htmlspecialchars($vehicle['transmission']) ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-users"></i> Seating Capacity: <?= htmlspecialchars($vehicle['seating_capacity']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted"><i class="fas fa-tachometer-alt"></i> Mileage: <?= htmlspecialchars($vehicle['mileage']) ?> miles</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted">
                                            <i class="fas fa-palette"></i>
                                            Color
                                            <span class="color-display" style="display: inline-block; width: 20px; height: 20px;margin-top:5px; background-color: <?= htmlspecialchars($vehicle['color']) ?>; border-radius: 5px; margin-right: 5px;"></span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span class="badge bg-success text-white" style="padding: 5px 10px; border-radius: 5px;">
                                                Price: Rs.<?= htmlspecialchars($vehicle['price']) ?>/day
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-muted"><i class="fas fa-align-left"></i> Description: <?= htmlspecialchars($vehicle['description']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <div class="container d-flex justify-content-center align-items-center mt-5">
                        <div class="form-section col-12 col-md-6">
                            <h3 class="text-center mb-4">Book Now</h3>
                            <form id="bookingForm" method="POST" action="controllers/add_booking_process.php">
                                <input type="hidden" name="vehicleId" value="<?= htmlspecialchars($vehicle['id']) ?>">

                                <div class="mb-3">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Pickup Location</label>
                                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter pickup location" required>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="withDriver" name="withDriver">
                                    <label class="form-check-label" for="withDriver">Get with a Driver</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                            </form>
                        </div>
                    </div>

                    <!-- Customer Reviews Section -->
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center mb-4">
                                    Customer Reviews
                                    <?php if (!empty($reviews)): ?>
                                        <small class="text-muted">
                                            (<?= count($reviews) ?> <?= count($reviews) === 1 ? 'Review' : 'Reviews' ?>)
                                        </small>
                                    <?php endif; ?>
                                </h3>

                                <!-- Add Review Form -->
                                <div class="row mb-5">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title text-center">Leave a Review</h4>
                                                <form id="reviewForm" method="POST" action="controllers/add_review_process.php">
                                                    <input type="hidden" name="vehicleId" value="<?= htmlspecialchars($vehicle['id']) ?>">

                                                    <div class="mb-3">
                                                        <label for="customerName" class="form-label">Your Name</label>
                                                        <input type="text" class="form-control" id="customerName" name="customerName"
                                                            placeholder="Enter your name" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Rating</label>
                                                        <div class="rating-select mb-2">
                                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                                <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                                                                <label for="star<?= $i ?>">★</label>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="comment" class="form-label">Your Review</label>
                                                        <textarea class="form-control" id="comment" name="comment"
                                                            rows="3" placeholder="Share your experience..." required></textarea>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Display Existing Reviews -->
                                <?php if (!empty($reviews)): ?>
                                    <div class="row">
                                        <?php foreach ($reviews as $review): ?>
                                            <div class="col-md-6 mb-4">
                                                <div class="review-box p-4 border rounded">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h5 class="mb-0"><?= htmlspecialchars($review['customer_name']) ?></h5>
                                                        <div class="star-rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'star-empty' ?>"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <p class="mb-2"><?= htmlspecialchars($review['comment']) ?></p>
                                                    <small class="text-muted">
                                                        Posted on <?= date('F j, Y', strtotime($review['created_at'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center">
                                        <p class="text-muted">No reviews yet. Be the first to share your experience!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Form validation
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            const rating = document.querySelector('input[name="rating"]:checked');
            if (!rating) {
                e.preventDefault();
                alert('Please select a rating');
            }
        });
    </script>
</body>

</html>