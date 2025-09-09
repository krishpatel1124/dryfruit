<?php
include("../includes/db.php");
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

// ✅ CREATE
if (isset($_POST['add_supplier'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $sql = "INSERT INTO supplier (email, password) VALUES ('$email', '$password')";
    $conn->query($sql);
}

// ✅ UPDATE
if (isset($_POST['update_supplier'])) {
    $id = $_POST['supplier_id'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $conn->query("UPDATE supplier SET email='$email', password='$password' WHERE supplier_id=$id");
    } else {
        $conn->query("UPDATE supplier SET email='$email' WHERE supplier_id=$id");
    }
}

// ✅ DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM supplier WHERE supplier_id=$id");
    header("Location: manage_suppliers.php");
    exit();
}

// Fetch all suppliers
$result = $conn->query("SELECT * FROM supplier ORDER BY supplier_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Suppliers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="text-center mb-4">👨‍💼 Manage Suppliers</h2>
  <a href="a_dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

  <!-- Add Supplier Form -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">Add New Supplier</div>
    <div class="card-body">
      <form method="post">
        <div class="row g-3">
          <div class="col-md-5"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
          <div class="col-md-5"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
          <div class="col-md-2"><button type="submit" name="add_supplier" class="btn btn-success w-100">Add</button></div>
        </div>
      </form>
    </div>
  </div>

  <!-- Supplier Table -->
  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Password (hashed)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="post">
          <td><?= $row['supplier_id']; ?><input type="hidden" name="supplier_id" value="<?= $row['supplier_id']; ?>"></td>
          <td><input type="email" name="email" value="<?= $row['email']; ?>" class="form-control"></td>
          <td><input type="password" name="password" class="form-control" placeholder="Leave blank to keep same"></td>
          <td>
            <button type="submit" name="update_supplier" class="btn btn-primary btn-sm">Update</button>
            <a href="manage_suppliers.php?delete=<?= $row['supplier_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete supplier?')">Delete</a>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
