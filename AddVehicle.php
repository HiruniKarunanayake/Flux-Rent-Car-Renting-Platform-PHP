<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
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

        .center-screen {
            display: flex;
            justify-content: center;
            align-items: center;
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
                <div class="form-container col-10 p-5">
                    <h2>Add Vehicle</h2>
                    <form action="controllers/add_vehicle_process.php" method="POST" enctype="multipart/form-data" id="vehicleForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vehicleMake">Make</label>
                                    <input type="text" class="form-control" name="vehicle_make" id="vehicleMake" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vehicleModel">Model</label>
                                    <input type="text" class="form-control" name="vehicle_model" id="vehicleModel" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vehicleYear">Year</label>
                                    <input type="number" class="form-control" name="vehicle_year" id="vehicleYear" min="1900" max="2024" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vehicleType">Vehicle Type</label>
                                    <select class="form-control" name="vehicle_type" id="vehicleType" required>
                                        <option value="" disabled selected>Select Vehicle Type</option>
                                        <option value="Sedan">Sedan</option>
                                        <option value="SUV">SUV</option>
                                        <option value="Van">Van</option>
                                        <option value="Truck">Truck</option>
                                        <option value="Coupe">Coupe</option>
                                        <option value="Hatchback">Hatchback</option>
                                        <option value="Convertible">Convertible</option>
                                        <option value="Wagon">Wagon</option>
                                        <option value="Pickup Truck">Pickup Truck</option>
                                        <option value="Sports Car">Sports Car</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fuelType">Fuel Type</label>
                                    <select class="form-control" name="fuel_type" id="fuelType" required>
                                        <option value="" disabled selected>Select Fuel Type</option>
                                        <option value="Gasoline">Gasoline</option>
                                        <option value="Diesel">Diesel</option>
                                        <option value="Electric">Electric</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="transmission">Transmission</label>
                                    <select class="form-control" name="transmission" id="transmission" required>
                                        <option value="" disabled selected>Select Transmission</option>
                                        <option value="Automatic">Automatic</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="seatingCapacity">Seating Capacity</label>
                                    <input type="number" class="form-control" name="seating_capacity" id="seatingCapacity" min="1" max="20" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mileage">Mileage (km)</label>
                                    <input type="number" class="form-control" name="mileage" id="mileage" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <input type="color" class="form-control" name="color" id="color" required>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="driverOption">Enter with Driver</label>
                                    <select class="form-control" name="driver" id="driverOption">
                                        <option value="with_driver">With Driver</option>
                                        <option value="without_driver">Without Driver</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="driverOption">Price per Day</label>
                                    <input type="number" class="form-control" name="price" id="price" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="des">Description</label>
                            <textarea class="form-control" name="description" id="des" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="images">Upload Images</label>
                            <input type="file" class="form-control-file" name="vehicle_images[]" id="images" multiple accept="image/*" required>
                            <div id="imagePreview"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>

    <script>
        const imagesInput = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');

        imagesInput.addEventListener('change', function() {
            imagePreview.innerHTML = ''; // Clear previous images
            const files = Array.from(this.files);

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create a container for the image and remove button
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('img-container');

                    // Create the image element
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.classList.add('img-preview');
                    imgElement.setAttribute('data-index', index);

                    // Create the remove button
                    const removeButton = document.createElement('button');
                    removeButton.innerHTML = 'Remove';
                    removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-btn');
                    removeButton.addEventListener('click', function() {
                        imgContainer.remove(); // Remove the entire container
                        removeImage(index); // Handle image removal logic
                    });

                    // Append the image and the button to the container
                    imgContainer.appendChild(imgElement);
                    imgContainer.appendChild(removeButton);

                    // Append the container to the preview section
                    imagePreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            });


        });

        function removeImage(index) {
            const dt = new DataTransfer();
            const {
                files
            } = imagesInput;

            // Add back files except the one at the removed index
            Array.from(files).forEach((file, i) => {
                if (index !== i) dt.items.add(file);
            });

            imagesInput.files = dt.files; // Update the file input
        }
    </script>
</body>

</html>