<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin-portal.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container pt-5">
        <div class="card pt-5">
            <div class="card-body">
                <h3 class="text-center mb-4">Admin Login</h3>
                <form id="loginForm" class="pb-5" onsubmit="return validateLoginForm();" action="controllers/admin-login-process.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Frontend Validation with JavaScript -->
    <script>
        function validateLoginForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === '' || password === '') {
                alert('Both fields are required.');
                return false;
            }

            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            // If all validations pass
            return true;
        }
    </script>
</body>

</html>