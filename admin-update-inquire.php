<?php

// Include database connection
require_once 'models/config.php';

// Get the inquiry ID from the query string
if (isset($_GET['id'])) {
    $inquiryId = $_GET['id'];

    // Fetch the inquiry details from the database using the Database class's search method
    $query = "SELECT * FROM inquiries WHERE id = $inquiryId";
    $result = Database::search($query);

    // Check if the inquiry exists
    if ($result->num_rows > 0) {
        $inquiry = $result->fetch_assoc();
    } else {
        // Redirect or show an error if inquiry is not found
        header("Location: admin-manage-inquires.php?error=Inquiry not found");
        exit;
    }
} else {
    // Redirect if no ID is provided
    header("Location: admin-manage-inquires.php?error=No inquiry ID provided");
    exit;
}

// Check if there's a success or error message passed via the query string
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Inquiry</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('images/background.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }

        .form-icon {
            color: #2348ff;
        }
    </style>
</head>

<body>

    <?php include 'header.php' ?>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php' ?>
        </div>

        <div class="content  col-10 mb-5 mt-5">
            <!-- Update Inquiry Form Section -->
            <div class="container">
                <div class="row justify-content-center mt-5 pt-2">
                    <div class="col-md-8">
                        <!-- Success and Error Messages -->
                        <?php if (!empty($successMessage)) : ?>
                            <div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                                <strong>Success!</strong> <?php echo $successMessage; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (!empty($errorMessage)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show mt-5" role="alert">
                                <strong>Error!</strong> <?php echo $errorMessage; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card form-card">
                            <h3 class="card-title text-center">Update Inquiry</h3>
                            <form id="updateInquiryForm" method="POST" action="controllers/update_inquire_process.php">
                                <input type="hidden" name="id" value="<?php echo $inquiryId; ?>">

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user form-icon"></i> Full Name
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($inquiry['name']); ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope form-icon"></i> Email Address
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($inquiry['email']); ?>" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone form-icon"></i> Phone Number
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($inquiry['phone']); ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">
                                        <i class="fas fa-info-circle form-icon"></i> Subject
                                    </label>
                                    <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($inquiry['subject']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">
                                        <i class="fas fa-comment form-icon"></i> Message
                                    </label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($inquiry['message']); ?></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary col-12">
                                    Update Inquiry
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>