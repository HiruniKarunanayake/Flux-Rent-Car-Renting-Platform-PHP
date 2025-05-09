<?php
session_start();

// Session validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once 'models/config.php';

// Fetch total earnings for the owner from the payments table
$totalEarningsQuery = "SELECT SUM(amount) AS total_earnings FROM payments WHERE status = 'Paid'";
$totalEarningsResult = Database::search($totalEarningsQuery);
$totalEarnings = $totalEarningsResult->fetch_assoc()['total_earnings'] ?? 0;

// Fetch payment history and owner details
$paymentsQuery = "
    SELECT p.*, o.first_name, o.last_name 
    FROM payments p 
    JOIN vehicle_owners o ON p.owner_id = o.id
    WHERE p.status = 'Paid' 
    ORDER BY p.payment_date DESC";
$paymentsResultHistory = Database::search($paymentsQuery);
$paymentsData = [];
if ($paymentsResultHistory && $paymentsResultHistory->num_rows > 0) {
    while ($row = $paymentsResultHistory->fetch_assoc()) {
        $paymentsData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Earnings and Payment History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="images/logo.png" alt="Logo" width="140" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin-portal.php">Portal</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="controllers/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin-login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex wrapper">
        <div><?php include 'admin_sidebar.php' ?></div>
        <div class="content col-10 mb-5 mt-5">
            <div class="container mt-5" style="margin-top: 20px;">
                <div class="mt-5">
                    <h2 class="mb-4">Total Earnings & Payment History</h2>

                    <!-- Total Earnings Section -->
                    <div class="card mb-4 border border-primary shadow-sm" style="background-color: #f9f9f9;">
                        <div class="card-body">
                            <h4 class="card-title text-primary"><i class="fas fa-wallet"></i> Total Earnings</h4>
                            <p class="card-text fs-5 text-dark">
                                <strong>Total Earnings: </strong>Rs<?php echo number_format($totalEarnings, 2); ?><br>
                            </p>
                        </div>
                    </div>

                    <!-- Payment History Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-history"></i> Payment History</h4>
                            <!-- Generate Report Button -->
                            <button class="btn btn-secondary btn-sm mb-2" id="generateReport">
                                <i class="fas fa-file-pdf"></i> Generate Report
                            </button>

                            <?php if (!empty($paymentsData)) : ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="paymentsTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Payment ID</th>
                                                <th>Owner Name</th>
                                                <th>Amount</th>
                                                <th>Payment Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="paymentsData">
                                            <?php foreach ($paymentsData as $payment) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></td>
                                                    <td>Rs <?php echo number_format($payment['amount'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                                                    <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <p>No payment history available.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('generateReport').addEventListener('click', function() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Title of the PDF
            doc.setFontSize(18);
            doc.text("Payment History Report", 20, 20);

            // Adding table header
            const tableColumn = ["Payment ID", "Owner Name", "Amount", "Payment Date", "Status"];
            const tableRows = [];

            // Fetching data directly from the HTML table
            document.querySelectorAll('#paymentsData tr').forEach(function(row) {
                const rowData = [];
                row.querySelectorAll('td').forEach(function(cell) {
                    rowData.push(cell.innerText);
                });
                tableRows.push(rowData);
            });

            // Use AutoTable plugin to generate the table in the PDF
            doc.autoTable({
                head: [tableColumn],
                body: tableRows,
                startY: 30,
                theme: 'striped'
            });

            // Save the PDF
            doc.save("payment-history-report.pdf");
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>