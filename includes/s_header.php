<?php  
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['supplier_id'])) {
    header("Location: ../login.php");
    exit();
}

include("includes/db.php");

// ✅ Fetch supplier profile image from DB
$supplier_id = $_SESSION['supplier_id'];
$result = $conn->query("SELECT profile_pic FROM supplier WHERE supplier_id = $supplier_id");
$user = $result->fetch_assoc();

// Fallback image if no profile uploaded
$profile_pic = !empty($user['profile_pic']) ? "../assets/uploads/" . $user['profile_pic'] : "../assets/images/profile.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier Dashboard - DryFruit Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .navbar { background-color: #5a3e2b; }
    .navbar-brand span { color: #f4b400; }
    .dropdown-menu a:hover { background-color: #f4b400; color: #fff !important; }
    footer { background: #5a3e2b; color:#fff; margin-top:30px; padding:20px 0; }
    .footer-bottom { background:#4a2f21; text-align:center; padding:10px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../index.php">DryFruit <span>Store</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupplier">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupplier">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="s_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="add_product.php">Add Product</a></li>
      </ul>

      <!-- ✅ Profile Image Dropdown (no username) -->
      <div class="dropdown">
        <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown">
          <img src="<?php echo $profile_pic; ?>" alt="profile" width="40" height="40" class="rounded-circle">
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
          <li><a class="dropdown-item" href="s_dashboard.php">My Dashboard</a></li>
          <li><a class="dropdown-item" href="s_edit_profile.php">Edit Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
