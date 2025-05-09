<?php
include 'header.php';
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            position: relative;
            min-height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/back.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: brightness(0.5) blur(5px);
            z-index: -1;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            margin-top: 60px;
            transition: transform 0.3s;
        }

        .form-card:hover {
            transform: translateY(-5px);
        }

        .form-icon {
            color: #2348ff;
        }

        h2 {
            text-align: center;
            font-weight: bold;
            color: #000;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: justify;
            color: #000;
            margin-bottom: 20px;
        }

        .highlight {
            color: #2348ff;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #2348ff;
            border-color: #2348ff;
        }
        
        .container {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            p {
                font-size: 1rem;
            }

            .form-card {
                padding: 20px;
                margin: 60px 15px;
            }
        }

        /* Glass effect for better readability */
        .glass-effect {
            position: relative;
            overflow: hidden;
        }

        .glass-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.1) 0%,
                rgba(255, 255, 255, 0.05) 100%
            );
            z-index: -1;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin: -1px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="form-card glass-effect">
                    <h2>About Us</h2>
                    <p>Welcome to <span class="highlight">FluxRent</span>, Sri Lanka's trusted online car rental platform.
                        We simplify car rentals by connecting vehicle owners with customers in need of a reliable ride.
                        Whether you're a car owner looking to earn extra income or a customer searching for the perfect car,
                        FluxRent is here to make the process smooth and easy.</p>
                    
                    <p>At FluxRent, we believe in creating opportunities for everyone. Vehicle owners can list their cars on our platform, giving them control over their rentals, 
                        while customers can explore a wide selection of vehicles for any occasion. From short trips to longer rentals, we've got the right vehicle to meet your needs.</p>
                    
                    <p>Our platform is designed to be user-friendly and secure, ensuring a seamless experience for both owners and renters. 
                        With just a few clicks, you can list or book a car quickly and hassle-free.</p>

                    <p class="text-center"><strong>Join FluxRent today and discover a smarter, more convenient way to rent a car!</strong></p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>