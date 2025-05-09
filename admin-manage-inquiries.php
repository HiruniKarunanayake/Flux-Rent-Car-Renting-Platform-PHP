<?php
// Include the header

// Fetch inquiries from the database
require_once 'models/config.php';
$query = "SELECT * FROM inquiries"; 
$inquiries = Database::search($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inquiries</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
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
                
                <?php
                if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-portal.php">Portal</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="controllers/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-login.php">Login</a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
    <div class="d-flex wrapper">
        <div>
            <?php include 'admin_sidebar.php'; ?>
        </div>

        <div class="content col-10 mb-5 mt-5">
            <div class="container mt-5 pt-5">
                <h2 class="text-center">Manage Inquiries</h2>

                <table class="table table-bordered table-striped mt-4">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $inquiries->fetch_assoc()) : ?>
                            <tr id="inquiry-row-<?php echo $row['id']; ?>">
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td>
                                    <!-- View Inquiry Button (Opens Modal) -->
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal-<?php echo $row['id']; ?>">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    

                                    <!-- Delete Inquiry -->
                                    <button class="btn btn-danger btn-sm" onclick="deleteInquiry(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="viewModal-<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel-<?php echo $row['id']; ?>">Inquiry Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></p>
                                            <p><strong>Subject:</strong> <?php echo htmlspecialchars($row['subject']); ?></p>
                                            <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                                            <p><strong>Created Date:</strong> <?php echo htmlspecialchars($row['createdDate']); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function deleteInquiry(inquiryId) {
            if (confirm("Are you sure you want to delete this inquiry?")) {
                // Redirect to the delete process script with the inquiry ID
                window.location.href = `controllers/inquire_delete_process.php?id=${inquiryId}`;
            }
        }
    </script>

</body>

</html>