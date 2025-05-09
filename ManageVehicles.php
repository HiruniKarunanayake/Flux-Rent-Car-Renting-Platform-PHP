<?php
session_start();

// Check if user is logged in and has 'owner' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php'; // Include database configuration

$owner_id = $_SESSION['user_id']; // Get the owner's user_id from the session

// Fetch vehicles that belong to the logged-in owner
Database::setUpConnection(); // Set up the database connection
$query = "SELECT * FROM vehicles WHERE owner = '$owner_id'";
$vehicles = Database::search($query); // Fetch vehicles

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            height: 100%;
        }

        .wrapper {
            min-height: 100%;
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
            <div class="container mt-5">
                <div class="container mt-3">
                    <div class="d-flex justify-content-end">
                        <a href="addVehicle.php" class="btn btn-primary">Add Vehicle</a>
                    </div>
                </div>

                <h1>Manage Vehicles</h1>

                <!-- Search Bar -->
                <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search vehicles...">

                <!-- Vehicle Table -->
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Vehicle Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vehicleTableBody">
                        <?php if ($vehicles && $vehicles->num_rows > 0) : ?>
                            <?php while ($vehicle = $vehicles->fetch_assoc()) : ?>
                                <tr id="vehicle-row-<?php echo $vehicle['id']; ?>">
                                    <td><?php echo htmlspecialchars($vehicle['make']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['model']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['year']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['type']); ?></td>
                                    <td>
                                        <a href="updateVehicle.php?vehicle_id=<?php echo $vehicle['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $vehicle['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5">No vehicles found for this owner.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this vehicle?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>

    <script>
        let vehicleIdToDelete;

        // Function to confirm deletion
        function confirmDelete(id) {
            vehicleIdToDelete = id;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Function to delete vehicle
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            // Redirect directly to deleteVehicle.php with vehicleIdToDelete
            window.location.href = `controllers/deleteVehicle.php?id=${vehicleIdToDelete}`;
        });

        // Search functionality
        document.getElementById('searchBar').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('#vehicleTableBody tr');
            rows.forEach(row => {
                const make = row.children[0].innerText.toLowerCase();
                const model = row.children[1].innerText.toLowerCase();
                const year = row.children[2].innerText.toLowerCase();
                const type = row.children[3].innerText.toLowerCase();

                if (make.includes(searchText) || model.includes(searchText) || year.includes(searchText) || type.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>