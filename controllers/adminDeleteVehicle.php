<?php
require_once '../models/Vehicle.php';

// Check if vehicle ID is provided
if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];
    $vehicleModel = new Vehicle();

    if ($vehicleModel->adminDeleteVehicleById($vehicleId)) {
        header('Location: ../admin-manage-vehicles.php');
        exit();
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Vehicle ID is missing']);
}
