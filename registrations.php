<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/back2.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            color: white;
        }

        .card {
            background-color: rgba(195, 195, 195, 0.8);
            border: none;
        }

        .card img {
            height: 200px;
            object-fit: cover;
        }

        .container {
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <?php
    include 'header.php'
    ?>
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-4">
                <div class="card mt-1">
                    <img src="images/7197526.jpg" class="card-img-top" alt="Customer Image">
                    <div class="card-body">
                        <h5 class="card-title">Customer Registration</h5>
                        <p class="card-text">Sign up as a customer to enjoy our services.</p>
                        <a href="customer-signup.php" class="btn btn-primary">Sign Up as Customer</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-1">
                    <img src="images/654.jpg" class="card-img-top" alt="Vehicle Owner Image">
                    <div class="card-body">
                        <h5 class="card-title">Vehicle Owner Registration</h5>
                        <p class="card-text">Register your vehicle and manage your fleet.</p>
                        <a href="vehicle-owner-signup.php" class="btn btn-primary">Sign Up as Vehicle Owner</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-1">
                    <img src="images/6387991.jpg" class="card-img-top" alt="Vehicle Driver Image">
                    <div class="card-body">
                        <h5 class="card-title">Vehicle Driver Registration</h5>
                        <p class="card-text">Join us as a driver and start earning.</p>
                        <a href="vehicle-driver-signup.php" class="btn btn-primary">Sign Up as Driver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include 'footer.php'
    ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>