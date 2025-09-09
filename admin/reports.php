<?php
include("../includes/db.php");
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

// Get total customers
$customers = $conn->query("SELECT COUNT(*) AS total FROM customer")->fetch_assoc()['total'];

// Get total orders
$orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];

// Get total revenue
$revenue = $conn->query("SELECT SUM(total_amount) AS total FROM orders WHERE status != 'cancelled'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="text-center mb-4">📊 Reports</h2>
  <a href="a_dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card text-center p-4 shadow">
        <h4>Total Customers</h4>
        <p class="fs-3 fw-bold"><?= $customers; ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center p-4 shadow">
        <h4>Total Orders</h4>
        <p class="fs-3 fw-bold"><?= $orders; ?></p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-center p-4 shadow">
        <h4>Total Revenue (₹)</h4>
        <p class="fs-3 fw-bold"><?= number_format($revenue ?? 0, 2); ?></p>
      </div>
    </div>
  </div>
</div>
</body>
</html>
