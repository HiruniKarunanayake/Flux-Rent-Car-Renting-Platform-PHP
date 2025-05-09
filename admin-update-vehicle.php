<?php
// updateVehicle.php

require_once 'models/config.php';

// Check if `vehicle_id` is provided in the URL
if (isset($_GET['vehicle_id'])) {
    $id = $_GET['vehicle_id'];

    // Fetch vehicle data from the database using the provided id
    $resultVehicle = Database::search("SELECT * FROM vehicles WHERE `id` = '" . $id . "'");
    $vehicle = $resultVehicle->fetch_assoc();

    // Check if the vehicle exists
    if (!$vehicle) {
        // Return JSON if the vehicle is not found
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Vehicle not found']);
        exit();
    }

    // Fetch vehicle images from the database
    $resultImages = Database::search("SELECT * FROM vehicle_images WHERE `vehicle_id` = '" . $id . "'");
    $images = [];
    while ($image = $resultImages->fetch_assoc()) {
        $images[] = $image['image_path']; // Collect image paths
    }
} else {
    // Return JSON if no `vehicle_id` is provided
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No vehicle ID provided']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .img-container {
            position: relative;
            display: inline-block;
            /* This makes sure all images render next to each other */
            margin-right: 10px;
            /* Add some space between images */
            margin-bottom: 10px;
            margin-top: 10px;
            /* Add some space between rows of images */
        }

        .img-preview {
            width: 150px;
            /* Set a fixed size for the images */
            height: 150px;
            border-radius: 5px;
            object-fit: cover;
            /* Ensures the image fits the container without distortion */
            display: block;
        }

        .remove-btn {
            position: absolute;
            bottom: 10px;
            /* Position the button at the bottom */
            left: 50%;
            transform: translateX(-50%);
            /* Center the button horizontally */
            background-color: rgba(255, 0, 0, 0.7);
            /* Semi-transparent red background for better visibility */
            color: white;
            padding: 5px;
            font-size: 12px;
        }

        .form-container {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
        }

        body,
        html {
            height: 100%;
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
            <div class="container-fluid center-screen">
                <h2>Update Vehicle</h2>
                <form action="controllers/update_vehicle_process.php" method="POST" enctype="multipart/form-data" id="vehicleForm">
                    <!-- Hidden input to store vehicle ID -->
                    <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicleMake">Make</label>
                                <input type="text" class="form-control" name="vehicle_make" id="vehicleMake" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicleModel">Model</label>
                                <input type="text" class="form-control" name="vehicle_model" id="vehicleModel" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicleYear">Year</label>
                                <input type="number" class="form-control" name="vehicle_year" id="vehicleYear" min="1900" max="2024" value="<?= $vehicle['year'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicleType">Vehicle Type</label>
                                <select class="form-control" name="vehicle_type" id="vehicleType" required>
                                    <option value="" disabled>Select Vehicle Type</option>
                                    <option value="Sedan" <?= $vehicle['type'] == 'Sedan' ? 'selected' : '' ?>>Sedan</option>
                                    <option value="SUV" <?= $vehicle['type'] == 'SUV' ? 'selected' : '' ?>>SUV</option>
                                    <option value="Van" <?= $vehicle['type'] == 'Van' ? 'selected' : '' ?>>Van</option>
                                    <option value="Truck" <?= $vehicle['type'] == 'Truck' ? 'selected' : '' ?>>Truck</option>
                                    <option value="Coupe" <?= $vehicle['type'] == 'Coupe' ? 'selected' : '' ?>>Coupe</option>
                                    <option value="Hatchback" <?= $vehicle['type'] == 'Hatchback' ? 'selected' : '' ?>>Hatchback</option>
                                    <option value="Convertible" <?= $vehicle['type'] == 'Convertible' ? 'selected' : '' ?>>Convertible</option>
                                    <option value="Wagon" <?= $vehicle['type'] == 'Wagon' ? 'selected' : '' ?>>Wagon</option>
                                    <option value="Pickup Truck" <?= $vehicle['type'] == 'Pickup Truck' ? 'selected' : '' ?>>Pickup Truck</option>
                                    <option value="Sports Car" <?= $vehicle['type'] == 'Sports Car' ? 'selected' : '' ?>>Sports Car</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fuelType">Fuel Type</label>
                                <select class="form-control" name="fuel_type" id="fuelType" required>
                                    <option value="" disabled>Select Fuel Type</option>
                                    <option value="Gasoline" <?= $vehicle['fuel_type'] == 'Gasoline' ? 'selected' : '' ?>>Gasoline</option>
                                    <option value="Diesel" <?= $vehicle['fuel_type'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                                    <option value="Electric" <?= $vehicle['fuel_type'] == 'Electric' ? 'selected' : '' ?>>Electric</option>
                                    <option value="Hybrid" <?= $vehicle['fuel_type'] == 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="transmission">Transmission</label>
                                <select class="form-control" name="transmission" id="transmission" required>
                                    <option value="" disabled>Select Transmission</option>
                                    <option value="Automatic" <?= $vehicle['transmission'] == 'Automatic' ? 'selected' : '' ?>>Automatic</option>
                                    <option value="Manual" <?= $vehicle['transmission'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="seatingCapacity">Seating Capacity</label>
                                <input type="number" class="form-control" name="seating_capacity" id="seatingCapacity" min="1" max="20" value="<?= $vehicle['seating_capacity'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mileage">Mileage (km)</label>
                                <input type="number" class="form-control" name="mileage" id="mileage" min="0" value="<?= $vehicle['mileage'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="color" class="form-control" name="color" id="color" value="<?= $vehicle['color'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="owner">Vehicle Owner</label>
                        <input type="text" class="form-control" name="owner" id="owner" value="<?= htmlspecialchars($vehicle['owner']) ?>" disabled>
                    </div>

                    <div class="form-group">
                        <label for="driverOption">Enter with Driver</label>
                        <select class="form-control" name="driver" id="driverOption" required>
                            <option value="with_driver" <?= $vehicle['driver'] == 'with_driver' ? 'selected' : '' ?>>With Driver</option>
                            <option value="without_driver" <?= $vehicle['driver'] == 'without_driver' ? 'selected' : '' ?>>Without Driver</option>
                        </select>
                    </div>
                    <div class="row">

                        <div class="form-group">
                            <label for="images">Upload New Images</label>
                            <input type="file" class="form-control-file" name="vehicle_images[]" id="images" multiple accept="image/*">
                            <div id="imagePreview"></div>
                        </div>

                        <!-- Existing images will be shown here -->
                        <div class="form-group">
                            <label>Current Images</label>
                            <div id="currentImagesPreview" class="d-flex flex-wrap"></div>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">Update Vehicle</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vehicleImages = <?= json_encode($images) ?>; // Correct reference to the $images array
            const currentImagesPreview = document.getElementById('currentImagesPreview');

            // Display current images for preview
            vehicleImages.forEach((imageUrl) => {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('img-container', 'm-2');

                const imgElement = document.createElement('img');
                imgElement.src = 'controllers/' + imageUrl;
                imgElement.classList.add('img-preview', 'img-thumbnail');
                imgElement.style.width = '150px';
                imgElement.style.height = 'auto';

                const removeButton = document.createElement('button');
                removeButton.innerHTML = 'Remove';
                removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'mt-2');
                removeButton.addEventListener('click', function() {
                    imgContainer.remove(); // Simple client-side removal (you should implement server-side removal as well)
                });

                imgContainer.appendChild(imgElement);
                imgContainer.appendChild(removeButton);
                currentImagesPreview.appendChild(imgContainer);
            });
        });
    </script>
</body>

</html>