<?php
include("../includes/db.php");
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

// ✅ Fetch order details
$sql = "SELECT o.*, c.name AS customer_name, c.email, c.phone 
        FROM orders o 
        JOIN customer c ON o.customer_id = c.customer_id
        WHERE o.order_id = $order_id";
$order = $conn->query($sql)->fetch_assoc();

if (!$order) {
    echo "<div class='alert alert-danger text-center'>Order not found!</div>";
    exit();
}

// ✅ Fetch order items
$sql_items = "SELECT oi.quantity, oi.price, p.name, p.image_url 
              FROM order_items oi
              JOIN product p ON oi.product_id = p.product_id
              WHERE oi.order_id = $order_id";
$items = $conn->query($sql_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order #<?= $order_id; ?> Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="text-center mb-4">📦 Order #<?= $order_id; ?> Details</h2>
  <a href="orders.php" class="btn btn-secondary mb-3">⬅ Back to Orders</a>

  <!-- Order Info -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-dark text-white">Order Information</div>
    <div class="card-body">
      <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
      <p><strong>Mobile:</strong> <?= htmlspecialchars($order['phone']); ?></p>
      <p><strong>Order Date:</strong> <?= $order['order_date']; ?></p>
      <p><strong>Status:</strong> 
        <span class="badge bg-warning text-dark"><?= ucfirst($order['status']); ?></span>
      </p>
      <p><strong>Total Amount:</strong> ₹<?= number_format($order['total_amount'], 2); ?></p>
    </div>
  </div>

  <!-- Items Table -->
  <div class="card shadow">
    <div class="card-header bg-primary text-white">Ordered Items</div>
    <div class="card-body">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Total (₹)</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $grand_total = 0;
          while ($item = $items->fetch_assoc()):
              $line_total = $item['price'] * $item['quantity'];
              $grand_total += $line_total;
          ?>
          <tr>
            <td><img src="<?= htmlspecialchars($item['image_url']); ?>" width="70" height="70" class="rounded"></td>
            <td><?= htmlspecialchars($item['name']); ?></td>
            <td><?= number_format($item['price'], 2); ?></td>
            <td><?= $item['quantity']; ?></td>
            <td><?= number_format($line_total, 2); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot class="table-light">
          <tr>
            <th colspan="4" class="text-end">Grand Total</th>
            <th>₹<?= number_format($grand_total, 2); ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
</body>
</html>
