<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - DryFruit Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7fb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Navbar */
    .navbar {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
    }
    .navbar-brand {
      font-weight: bold;
      color: #fff !important;
    }
    .logout-btn {
      background: #dc3545;
      color: #fff;
      border-radius: 30px;
      padding: 6px 16px;
    }
    .logout-btn:hover {
      background: #c82333;
    }
    /* Banner */
    .dashboard-banner {
      background: linear-gradient(135deg, #2a5298, #1e3c72);
      color: #fff;
      padding: 40px 20px;
      border-radius: 12px;
      text-align: center;
      margin-top: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .dashboard-banner h2 {
      font-weight: bold;
      margin-bottom: 10px;
    }
    .dashboard-banner p {
      font-size: 1rem;
      opacity: 0.9;
    }
    /* Cards */
    .dashboard-cards {
      margin-top: 40px;
    }
    .dashboard-cards .card {
      border: none;
      border-radius: 12px;
      transition: all 0.3s ease;
      background: #fff;
      padding: 25px 15px;
    }
    .dashboard-cards .card:hover {
      transform: translateY(-8px);
      box-shadow: 0px 6px 18px rgba(0,0,0,0.15);
    }
    .dashboard-cards .card h5 {
      font-weight: 600;
      color: #1e3c72;
    }
    .dashboard-cards .btn {
      border-radius: 30px;
      padding: 6px 16px;
      font-weight: bold;
    }
    .icon-circle {
      width: 60px;
      height: 60px;
      background: #e6edff;
      color: #2a5298;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 28px;
    }
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

  <div class="container">
    <!-- Welcome Banner -->
    <div class="dashboard-banner">
      <h2>Welcome, Admin 👋</h2>
      <p>Manage your store efficiently from one place</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4 dashboard-cards mt-4">
      <!-- Products -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-box-seam"></i></div>
          <h5 class="card-title">📦 Manage Products</h5>
          <a href="products.php" class="btn btn-primary mt-2">View</a>
        </div>
      </div>

      <!-- Product Approvals -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-check2-circle"></i></div>
          <h5 class="card-title">✅ Product Approvals</h5>
          <a href="admin_product_approve.php" class="btn btn-success mt-2">Approve</a>
        </div>
      </div>

      <!-- Customers -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-people"></i></div>
          <h5 class="card-title">👥 Manage Customers</h5>
          <a href="customers.php" class="btn btn-primary mt-2">View</a>
        </div>
      </div>

      <!-- Orders -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-receipt"></i></div>
          <h5 class="card-title">📑 Manage Orders</h5>
          <a href="orders.php" class="btn btn-primary mt-2">View</a>
        </div>
      </div>

      <!-- Reports -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-graph-up"></i></div>
          <h5 class="card-title">📊 Reports</h5>
          <a href="reports.php" class="btn btn-primary mt-2">View</a>
        </div>
      </div>

      <!-- Messages -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-envelope"></i></div>
          <h5 class="card-title">📨 User Messages</h5>
          <a href="manage_messages.php" class="btn btn-primary mt-2">View</a>
        </div>
      </div>

      <!-- Suppliers -->
      <div class="col-md-3">
        <div class="card text-center h-100">
          <div class="icon-circle"><i class="bi bi-person-badge"></i></div>
          <h5 class="card-title">👨‍💼 Manage Suppliers</h5>
          <a href="manage_suppliers.php" class="btn btn-primary mt-2">View</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
