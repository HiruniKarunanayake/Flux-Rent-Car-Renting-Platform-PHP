<?php
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="images/logo.png" alt="Logo" width="140" height="40" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="car-booking.php">Cars</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about-us.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <?php
                if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-portal.php">Portal</a>
                    </li>
                <?php
                }
                ?>
                <?php
                if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'owner') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="vehicle-owner-portal.php">Portal</a>
                    </li>
                <?php
                }
                ?>
                <?php
                if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'driver') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="driver-portal.php">Portal</a>
                    </li>
                <?php
                }
                ?>
                <?php
                if (isset($_SESSION['user_id']) && $_SESSION['role'] != 'admin') {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="customer-bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="profile.php">Profile</a>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="registrations.php">Sign Up</a>
                    </li>
                <?php
                }
                ?>

            </ul>
        </div>
    </div>
</nav>