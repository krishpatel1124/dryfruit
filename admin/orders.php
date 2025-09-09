<?php
include("../includes/db.php");
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status='$status' WHERE order_id=$order_id");
}

$sql = "SELECT o.order_id, o.order_date, o.status, o.total_amount, c.name AS customer_name
        FROM orders o
        JOIN customer c ON o.customer_id = c.customer_id
        ORDER BY o.order_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="text-center mb-4">📑 Manage Orders</h2>
  <a href="a_dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

  <table class="table table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>Customer</th><th>Date</th><th>Status</th><th>Total (₹)</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['order_id']; ?></td>
        <td><?= $row['customer_name']; ?></td>
        <td><?= $row['order_date']; ?></td>
        <td>
          <form method="post" class="d-flex">
            <input type="hidden" name="order_id" value="<?= $row['order_id']; ?>">
            <select name="status" class="form-control">
              <option value="pending"   <?= $row['status']=='pending'?'selected':''; ?>>Pending</option>
              <option value="Completed" <?= $row['status']=='Completed'?'selected':''; ?>>Completed</option>
              <option value="cancelled" <?= $row['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
            </select>
            <button type="submit" name="update_status" class="btn btn-primary btn-sm ms-2">Update</button>
          </form>
        </td>
        <td><?= number_format($row['total_amount'],2); ?></td>
        <td>
          <a href="view_order.php?id=<?= $row['order_id']; ?>" class="btn btn-info btn-sm">View</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
