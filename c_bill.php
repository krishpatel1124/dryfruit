<?php
include("includes/c_header.php");
include("includes/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "<div class='alert alert-danger text-center'>Order not selected.</div>";
    include("includes/c_footer.php");
    exit();
}

$order_id = intval($_GET['order_id']);
$customer_id = $_SESSION['customer_id'];

// Fetch order
$order_sql = "SELECT * FROM orders WHERE order_id = $order_id AND customer_id = $customer_id";
$order_result = $conn->query($order_sql);

if (!$order_result || $order_result->num_rows === 0) {
    echo "<div class='alert alert-danger text-center'>Order not found.</div>";
    include("includes/c_footer.php");
    exit();
}
$order = $order_result->fetch_assoc();

// Fetch bill
$bill_sql = "SELECT * FROM bill WHERE order_id = $order_id AND customer_id = $customer_id";
$bill_result = $conn->query($bill_sql);
$bill = $bill_result->fetch_assoc();

// Fetch order items
$items_sql = "SELECT oi.quantity, oi.price, p.name, p.image_url
              FROM order_items oi
              JOIN product p ON oi.product_id = p.product_id
              WHERE oi.order_id = $order_id";
$items = $conn->query($items_sql);
?>

<div class="container my-5">
  <div class="card shadow-lg">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h4>🧾 Invoice - DryFruit Store</h4>
      <button onclick="window.print()" class="btn btn-warning btn-sm">🖨️ Print Invoice</button>
    </div>
    <div class="card-body">
      <!-- Store & Customer Info -->
      <div class="row mb-4">
        <div class="col-md-6">
          <h5>From:</h5>
          <p>
            <strong>DryFruit Store</strong><br>
            Valsad, Gujarat<br>
            Email: support@dryfruitstore.com<br>
            Phone: +91-9876543210
          </p>
        </div>
        <div class="col-md-6 text-end">
          <h5>To:</h5>
          <p>
            <strong><?php echo $_SESSION['name']; ?></strong><br>
            Email: <?php echo $_SESSION['email']; ?><br>
          </p>
        </div>
      </div>

      <!-- Order Info -->
      <div class="row mb-4">
        <div class="col-md-3"><strong>Bill ID:</strong> #<?php echo $bill['bill_id']; ?></div>
        <div class="col-md-3"><strong>Bill Date:</strong> <?php echo $bill['bill_date']; ?></div>
        <div class="col-md-3"><strong>Order ID:</strong> #<?php echo $order['order_id']; ?></div>
        <div class="col-md-3"><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></div>
      </div>

      <!-- Items -->
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
            <td><img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="60" height="60" class="rounded"></td>
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
            <th>₹<?php echo number_format($bill['total_amount'], 2); ?></th>
          </tr>
        </tfoot>
      </table>

      <p class="text-center mt-4">🙏 Thank you for shopping with DryFruit Store!</p>
    </div>
  </div>
</div>

<?php include("includes/c_footer.php"); ?>
