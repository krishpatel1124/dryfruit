<?php
include("includes/c_header.php");
include("includes/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// ✅ Cancel order logic
if (isset($_GET['cancel'])) {
    $cancel_id = intval($_GET['cancel']);
    // Only allow cancelling if status = Pending
    $conn->query("UPDATE orders SET status = 'Cancelled' WHERE order_id = $cancel_id AND customer_id = $customer_id AND status = 'Pending'");
    $_SESSION['message'] = "Order #$cancel_id has been cancelled.";
    header("Location: c_order.php");
    exit();
}

// Fetch all orders of this customer
$sql = "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC";
$orders = $conn->query($sql);
?>

<div class="container my-5">
  <h2 class="text-center mb-4">📦 My Orders</h2>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info text-center">
      <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <?php if ($orders && $orders->num_rows > 0): ?>
    <?php while($order = $orders->fetch_assoc()): ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
          <div>
            <strong>Order #<?php echo $order['order_id']; ?></strong> 
            <span class="badge bg-warning text-dark ms-2"><?php echo ucfirst($order['status']); ?></span>
          </div>
          <div>Date: <?php echo $order['order_date']; ?></div>
        </div>
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
              $order_id = $order['order_id'];
              $items_sql = "SELECT oi.quantity, oi.price, p.name, p.image_url
                            FROM order_items oi
                            JOIN product p ON oi.product_id = p.product_id
                            WHERE oi.order_id = $order_id";
              $items = $conn->query($items_sql);

              $order_total = 0;
              while ($item = $items->fetch_assoc()):
                $line_total = $item['price'] * $item['quantity'];
                $order_total += $line_total;
              ?>
              <tr>
                <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="70" height="70" class="rounded"></td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($line_total, 2); ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Grand Total</th>
                <th>₹<?php echo number_format($order_total, 2); ?></th>
              </tr>
            </tfoot>
          </table>

          


          <!-- Action Buttons -->
          <!-- Action Buttons -->
<div class="d-flex justify-content-end gap-2">
  <?php if ($order['status'] === 'Pending'): ?>
    <a href="c_bill.php?order_id=<?php echo $order_id; ?>" class="btn btn-sm btn-primary">🧾 View Bill</a>
    <a href="c_order.php?cancel=<?php echo $order_id; ?>" 
       class="btn btn-sm btn-danger" 
       onclick="return confirm('Are you sure you want to cancel this order?')">❌ Cancel Order</a>
  <?php elseif ($order['status'] === 'Completed'): ?>
    <a href="c_bill.php?order_id=<?php echo $order_id; ?>" class="btn btn-sm btn-primary">🧾 View Bill</a>
  <?php endif; ?>
</div>

        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-info text-center">No orders found. <a href="c_dashboard.php">Shop now</a></div>
  <?php endif; ?>
</div>

<?php include("includes/c_footer.php"); ?>
