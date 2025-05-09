<?php
session_start();
require_once 'models/config.php';
require_once 'models/Customer.php';
require_once 'models/VehicleOwner.php';
require_once 'models/VehicleDriver.php';
// Check if session exists, redirect to login if not
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit();
}

// Fetch user profile data
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

$userData = null;

// Fetch the user's profile based on their role
switch ($role) {
    case 'customer':
        $userData = Customer::getProfile($userId);
        break;
    case 'driver':
        $userData = VehicleDriver::getProfile($userId);
        break;
    case 'owner':
        $userData = VehicleOwner::getProfile($userId);
        break;
    default:
        header('Location: login.php');
        exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/background.webp');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
        }

        .form-row .form-group {
            flex: 0 0 48%;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4">Update Profile</h3>
                <form id="updateProfileForm" onsubmit="return validateForm();" action="controllers/update_profile_process.php" method="POST">
                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo $userData['first_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $userData['last_name']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
                        <small class="form-text">For account verification and communication.</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $userData['phone']; ?>" required>
                        <small class="form-text">For contact and verification.</small>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text">Leave blank to keep your current password.</small>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $userData['dob']; ?>" required>
                        <small class="form-text">To verify age and eligibility.</small>
                    </div>

                    <!-- Conditional Fields Based on Role -->
                    <?php if ($role == 'customer') { ?>
                        <div class="form-group">
                            <label for="preferredContact">Preferred Contact Method</label>
                            <select class="form-control" id="preferredContact" name="preferredContactMethod" required>
                                <option value="email" <?php if ($userData['preferred_contact_method'] == 'email') echo 'selected'; ?>>Email</option>
                                <option value="phone" <?php if ($userData['preferred_contact_method'] == 'phone') echo 'selected'; ?>>Phone</option>
                            </select>
                        </div>
                    <?php } elseif ($role == 'driver') { ?>
                        <div class="form-group">
                            <label for="emergencyContactName">Emergency Contact Name</label>
                            <input type="text" class="form-control" id="emergencyContactName" name="emergencyContactName" value="<?php echo $userData['emergency_contact_name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="emergencyContactPhone">Emergency Contact Phone</label>
                            <input type="tel" class="form-control" id="emergencyContactPhone" name="emergencyContactPhone" value="<?php echo $userData['emergency_contact_phone']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="drivingExperience">Driving Experience (in years)</label>
                            <input type="number" class="form-control" id="drivingExperience" name="driving_experience" value="<?php echo $userData['driving_experience']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="your day charg">Enter Your Charg Per Day</label>
                            <input type="number" class="form-control" id="yourDayCharg" name="yourDayCharg" value="<?php echo $userData['driving_experience']; ?>" required>
                        </div>
                    <?php } ?>

                    <button type="submit" class="btn btn-primary w-100 mt-3">Update Profile</button>
                </form>

                <a href="dashboard.php" class="btn btn-secondary w-100 mt-3">Cancel</a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Frontend Validation with JavaScript -->
    <script>
        function validateForm() {
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value.trim();
            const dob = document.getElementById('dob').value;

            // Email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // Phone validation (simple numeric check)
            const phoneRegex = /^[0-9]{10}$/;

            if (firstName === '' || lastName === '' || email === '' || phone === '' || dob === '') {
                alert('All fields except password are required.');
                return false;
            }

            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            if (!phoneRegex.test(phone)) {
                alert('Please enter a valid 10-digit phone number.');
                return false;
            }

            if (password.length > 0 && password.length < 6) {
                alert('Password must be at least 6 characters long if entered.');
                return false;
            }

            if (new Date(dob).getFullYear() > new Date().getFullYear() - 18) {
                alert('You must be at least 18 years old.');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
