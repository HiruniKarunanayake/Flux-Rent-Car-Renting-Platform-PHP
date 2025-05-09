<style>
    /* Custom styling for the sidebar */
    .sidebar {
        min-height: 100vh;
        background-color: #f8f9fa;
        position: fixed;
        left: 0;
        padding-top: 50px;
        width: 250px;
    }

    .sidebar .nav-link {
        font-size: 18px;
        padding: 15px;
        border: 1px solid transparent; /* Add a transparent border for consistent spacing */
        border-radius: 4px; /* Rounded corners for buttons */
        transition: background-color 0.3s, border-color 0.3s; /* Smooth transition for hover effect */
    }

    .sidebar .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #0056b3; /* Change border color for active link */
    }

    .sidebar .nav-link:hover {
        border-color: #007bff; /* Border color change on hover */
    }

    .content {
        margin-left: 250px;
        padding: 20px;
        min-height: 100%;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .content {
            margin-left: 0;
        }
    }
</style>

<nav class="sidebar d-md-block bg-dark">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="driver-portal.php">
                    <i class="fas fa-calendar-check"></i> Bookings
                </a>
            </li>
        </ul>
    </div>
</nav>
