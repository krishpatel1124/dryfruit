<?php
include("../includes/db.php");
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM customer WHERE customer_id=$id");
    header("Location: customers.php");
    exit();
}

$result = $conn->query("SELECT * FROM customer ORDER BY customer_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Customers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="text-center mb-4">👥 Manage Customers</h2>
  <a href="a_dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['customer_id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['email']; ?></td>
        <td><?= $row['phone']; ?></td>
        <td>
          <a href="customers.php?delete=<?= $row['customer_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete customer?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
