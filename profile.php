<?php
session_start();
require_once 'models/config.php';
require_once 'models/Customer.php';
require_once 'models/VehicleOwner.php';
require_once 'models/VehicleDriver.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Initialize profile data variable
$profileData = null;

// Fetch the user's profile based on their role
switch ($role) {
    case 'customer':
        $profileData = Customer::getProfile($userId);
        break;
    case 'driver':
        $profileData = VehicleDriver::getProfile($userId);
        break;
    case 'owner':
        $profileData = VehicleOwner::getProfile($userId);
        break;
    default:
        header('Location: login.php');
        exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/car_profile.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(2px); 
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

        .profile-card {
            max-width: 600px;
            margin: 50px auto;
        }
    </style>
</head>

<body>
    <?php
    include 'header.php'
    ?>
    <div class="container pt-5">
        <div class="card profile-card pt-5">
            <div class="card-body text-center">
                <h3 class="card-title">Profile Details</h3>

                <!-- Display errors from session -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); // Clear error after displaying ?>
                <?php endif; ?>

                <?php if ($profileData): ?>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($profileData['first_name'] . ' ' . $profileData['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($profileData['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($profileData['phone']); ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($profileData['dob']); ?></p>

                    <?php if ($role == 'customer'): ?>
                        <p><strong>Preferred Contact Method:</strong> <?php echo htmlspecialchars($profileData['preferred_contact_method']); ?></p>
                    <?php elseif ($role == 'vehicle_driver'): ?>
                        <p><strong>Emergency Contact:</strong> <?php echo htmlspecialchars($profileData['emergency_contact_name']); ?></p>
                        <p><strong>Emergency Contact Phone:</strong> <?php echo htmlspecialchars($profileData['emergency_contact_phone']); ?></p>
                        <p><strong>Driving Experience:</strong> <?php echo htmlspecialchars($profileData['driving_experience']); ?> years</p>
                    <?php endif; ?>

                    <form method="POST" class="mt-4">
                        <a href="update-profile.php" class="btn btn-primary"><i class="fas fa-edit"></i> Update</a>
                        
                        <!-- Trigger the modal for delete confirmation -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Delete
                        </button>

                        <button type="submit" name="logout" class="btn btn-warning"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                <?php else: ?>
                    <p>Profile not found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade text-dark" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete your profile? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <!-- Form to delete the profile -->
                    <form action="controllers/delete_profile_process.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                        <input type="hidden" name="role" value="<?php echo $role; ?>">
                        <button type="submit" name="confirm_delete"class="btn btn-danger">Confirm Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'footer.php'
    ?>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
