<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: ../login.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

require_once '../models/Vehicle.php';

// Initialize Database connection (optional, since the Database methods call it)
Database::setUpConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $make = $_POST['vehicle_make'];
    $model = $_POST['vehicle_model'];
    $year = $_POST['vehicle_year'];
    $type = $_POST['vehicle_type'];
    $fuelType = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $seatingCapacity = $_POST['seating_capacity'];
    $mileage = $_POST['mileage'];
    $color = $_POST['color'];
    $owner = $owner_id ;
    $driver = $_POST['driver'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Collect images
    $images = $_FILES['vehicle_images'];

    // Initialize an empty errors array
    $errors = [];

    // Validation rules (same as before)
    if (empty($make)) {
        $errors[] = "Make is required.";
    }
    if (empty($model)) {
        $errors[] = "Model is required.";
    }
    if (empty($year) || !filter_var($year, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1900, "max_range" => date("Y")]])) {
        $errors[] = "Please provide a valid year.";
    }
    if (empty($type)) {
        $errors[] = "Vehicle type is required.";
    }
    if (empty($fuelType)) {
        $errors[] = "Fuel type is required.";
    }
    if (empty($transmission)) {
        $errors[] = "Transmission type is required.";
    }
    if (empty($seatingCapacity) || !filter_var($seatingCapacity, FILTER_VALIDATE_INT)) {
        $errors[] = "Please provide a valid seating capacity.";
    }
    if (empty($mileage) || !filter_var($mileage, FILTER_VALIDATE_INT)) {
        $errors[] = "Please provide valid mileage.";
    }
    if (empty($color)) {
        $errors[] = "Color is required.";
    }
    if (empty($owner)) {
        $errors[] = "Owner information is required.";
    }
    if (empty($price)) {
        $errors[] = "Price is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    // Driver selection validation
    if ($driver !== 'with_driver' && $driver !== 'without_driver') {
        $errors[] = "Driver selection is invalid.";
    }

    // Validate images
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if (empty($images['name'][0])) {
        $errors[] = "At least one image is required.";
    } else {
        foreach ($images['tmp_name'] as $key => $tmpName) {
            if ($images['size'][$key] > $maxFileSize) {
                $errors[] = "File " . $images['name'][$key] . " is too large. Maximum size is 5MB.";
            }
            if (!in_array($images['type'][$key], $allowedImageTypes)) {
                $errors[] = "File " . $images['name'][$key] . " is not a valid image type. Only JPG, PNG, and GIF are allowed.";
            }
        }
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        exit;
    }

    // If validation passes, proceed with saving the vehicle and uploading images
    $vehicle = new Vehicle();

    // Add the vehicle and handle image uploads
    if ($vehicle->addVehicle($make, $model, $year, $type, $fuelType, $transmission, $seatingCapacity, $mileage, $color, $owner, $driver, $images,$price,$description)) {
        // Success: Redirect or show a success message
        header('Location: ../ManageVehicles.php');
    } else {
        // Failure: Handle the error
        echo "Failed to add vehicle. Please try again.";
    }
} else {
    // Redirect if not a POST request
    header('Location: ../AddVehicle.php');
}
