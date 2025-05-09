<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Signup</title>
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
    <?php
    include 'header.php'
    ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4">Driver Signup</h3>
                <form id="driverSignupForm" onsubmit="return validateForm();" action="controllers/vehicle-driver-signup-process.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <small class="form-text">For account verification and communication.</small>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                        <small class="form-text">For contact and verification.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="form-text">For account security.</small>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                        <small class="form-text">To verify age and eligibility.</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emergencyContactName">Emergency Contact Name</label>
                            <input type="text" class="form-control" id="emergencyContactName" name="emergencyContactName" required>
                        </div>
                        <div class="form-group">
                            <label for="emergencyContactPhone">Emergency Contact Phone</label>
                            <input type="tel" class="form-control" id="emergencyContactPhone" name="emergencyContactPhone" required>
                        </div>
                       
                    </div>
                    
                    <div class="form-group">
                        <label for="drivingExperience">Driving Experience (Years)</label>
                        <input type="number" class="form-control" id="drivingExperience" name="drivingExperience" required min="0" step="1">
                        <small class="form-text">Enter your driving experience in years.</small>
                    </div>
                    <div class="form-group">
                            <label for="chargPerDay">Enter Your Charg Per Day</label>
                            <input type="number" class="form-control" id="chargPerDay" name="chargPerDays" required>
                        </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Sign Up</button>
                </form>
                <a href="login.php" class="btn btn-secondary w-100 mt-3">Already have an account? Log In</a>
            </div>
        </div>
    </div>
    <?php
    include 'footer.php'
    ?>

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
            const emergencyContactName = document.getElementById('emergencyContactName').value.trim();
            const emergencyContactPhone = document.getElementById('emergencyContactPhone').value.trim();
            const drivingExperience = document.getElementById('drivingExperience').value.trim();
            const chargPerDay = document.getElementById('chargPerDay').value.trim();


            // Email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // Phone validation (simple numeric check)
            const phoneRegex = /^[0-9]{10}$/;

            if (firstName === '' || lastName === '' || email === '' || phone === '' || password === '' || dob === '' || emergencyContactName === '' || emergencyContactPhone === '' || drivingExperience === '') {
                alert('All fields are required.');
                return false;
            }

            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            if (!phoneRegex.test(phone) || !phoneRegex.test(emergencyContactPhone)) {
                alert('Please enter valid 10-digit phone numbers.');
                return false;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters long.');
                return false;
            }

            if (new Date(dob).getFullYear() > new Date().getFullYear() - 18) {
                alert('You must be at least 18 years old to sign up.');
                return false;
            }

            if (isNaN(drivingExperience) || drivingExperience < 0) {
                alert('Please enter a valid number for driving experience.');
                return false;
            }

            // If all validations pass
            return true;
        }
    </script>
</body>

</html>