<?php
session_start();
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/s_header.php");

// Fetch supplier email for display
$supplier_id = $_SESSION['supplier_id'];
$sql = "SELECT email FROM supplier WHERE supplier_id = $supplier_id LIMIT 1";
$res = $conn->query($sql);
$supplier = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Supplier Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background-color: #f4f7fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  /* Banner */
  .dashboard-banner {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: #fff;
    padding: 40px 20px;
    border-radius: 12px;
    text-align: center;
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
  .dashboard-cards .card {
    border: none;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: #fff;
  }
  .dashboard-cards .card:hover {
    transform: translateY(-8px);
    box-shadow: 0px 6px 18px rgba(0,0,0,0.15);
  }
  .dashboard-cards .card h5 {
    font-weight: 600;
    color: #0072ff;
  }
  .dashboard-cards .btn {
    border-radius: 30px;
    padding: 8px 20px;
    font-weight: bold;
  }
  .icon-circle {
    width: 60px;
    height: 60px;
    background: #e9f2ff;
    color: #0072ff;
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

<div class="container my-5">
  <!-- Welcome Banner -->
  <div class="dashboard-banner mb-5">
    <h2>Welcome, Supplier 👋</h2>
    <p>You are logged in as <strong><?= htmlspecialchars($supplier['email']); ?></strong></p>
  </div>

  <!-- Dashboard Cards -->
  <div class="row g-4 dashboard-cards">
    <!-- Manage Products -->
    <div class="col-md-4">
      <div class="card text-center shadow-sm p-4 h-100">
        <div class="icon-circle"><i class="bi bi-box-seam"></i></div>
        <h5 class="card-title">📦 My Products</h5>
        <p class="text-muted">Add, edit or manage your products</p>
        <a href="s_products.php" class="btn btn-primary">Manage</a>
      </div>
    </div>

    <!-- Orders -->
    <div class="col-md-4">
      <div class="card text-center shadow-sm p-4 h-100">
        <div class="icon-circle"><i class="bi bi-receipt"></i></div>
        <h5 class="card-title">📑 My Orders</h5>
        <p class="text-muted">View customer orders for your products</p>
        <a href="s_order.php" class="btn btn-success">View</a>
      </div>
    </div>

    <!-- Profile -->
    <div class="col-md-4">
      <div class="card text-center shadow-sm p-4 h-100">
        <div class="icon-circle"><i class="bi bi-person-circle"></i></div>
        <h5 class="card-title">👤 My Profile</h5>
        <p class="text-muted">Update your account details</p>
        <a href="s_edit_profile.php" class="btn btn-info">Edit</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<?php include("includes/s_footer.php"); ?>
</body>
</html>
