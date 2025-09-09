<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

// Include DB connection
include("../includes/db.php"); // Adjust path if needed

// Handle Approve/Reject action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'approve') {
        $status = 'Active';
        $approval_date = date('Y-m-d');
    } elseif ($action == 'reject') {
        $status = 'Rejected';
        $approval_date = NULL;
    }

    $update_sql = "UPDATE product SET status='$status', approval_date=" . ($approval_date ? "'$approval_date'" : "NULL") . " WHERE product_id=$product_id";
    $conn->query($update_sql);
    header("Location: admin_product_approve.php");
    exit();
}

// Fetch pending products
$sql = "SELECT p.*, s.name AS supplier_name, c.name AS category_name
        FROM product p
        LEFT JOIN supplier s ON p.supplier_id = s.supplier_id
        LEFT JOIN category c ON p.category_id = c.category_id
        WHERE p.status='Pending'
        ORDER BY p.product_id DESC";
$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Product Approvals - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .navbar { background: linear-gradient(135deg, #1e3c72, #2a5298); }
        .navbar-brand { font-weight: bold; color: #fff !important; }
        .logout-btn { background: #dc3545; color: #fff; }
        .logout-btn:hover { background: #c82333; color: #fff; }
        footer { background-color: #343a40; color: #fff; padding: 15px 0; text-align: center; margin-top: 40px; }
        .table img { border-radius: 5px; }
        .btn-back { margin-bottom: 20px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="a_dashboard.php">Admin Panel</a>
            <div class="d-flex">
                <a href="a_logout.php" class="btn logout-btn">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Back to Dashboard Button -->
        <a href="a_dashboard.php" class="btn btn-secondary btn-back">← Back to Dashboard</a>

        <h2 class="text-center mb-4">📝 Pending Product Approvals</h2>

        <?php if($res->num_rows == 0): ?>
            <div class="alert alert-info text-center">No pending products.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Supplier</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['supplier_name']) ?></td>
                            <td><?= htmlspecialchars($row['category_name']) ?></td>
                            <td>₹<?= number_format($row['price'],2) ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td>
                                <?php if($row['image_url'] && file_exists('../uploads/products/'.$row['image_url'])): ?>
                                    <img src="../uploads/products/<?= $row['image_url'] ?>" alt="Product" width="80">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="admin_product_approve.php?action=approve&id=<?= $row['product_id'] ?>" class="btn btn-success btn-sm mb-1">Approve</a>
                                <a href="admin_product_approve.php?action=reject&id=<?= $row['product_id'] ?>" class="btn btn-danger btn-sm mb-1">Reject</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?= date('Y') ?> DryFruit Store | Made with ❤️
    </footer>
</body>
</html>
