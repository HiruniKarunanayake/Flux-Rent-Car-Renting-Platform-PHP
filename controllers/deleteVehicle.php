<?php
require_once '../models/Vehicle.php';

// Check if vehicle ID is provided
if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    // Initialize Vehicle model
    $vehicleModel = new Vehicle();

    // Delete the vehicle
    if ($vehicleModel->deleteVehicleById($vehicleId)) {
        header("Location: ../ManageVehicles.php?success=Vehicle+deleted+successfully");
        exit(); // Ensure script stops here after the redirect
    } else {
        // Redirect with error message in case of failure
        header("Location: ../ManageVehicles.php?error=Failed+to+delete+vehicle");
        exit();
    }
} else {
    // Redirect with error message if vehicle ID is missing
    header("Location: ../ManageVehicles.php?error=Vehicle+ID+is+missing");
    exit();
}
