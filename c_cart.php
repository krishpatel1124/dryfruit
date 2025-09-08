<?php
include("includes/c_header.php");
include("includes/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// ✅ Handle quantity update
if (isset($_POST['update_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = max(1, intval($_POST['quantity']));
    $conn->query("UPDATE cart SET quantity = $quantity WHERE cart_id = $cart_id AND customer_id = $customer_id");
    $_SESSION['message'] = "Cart updated!";
    header("Location: c_cart.php");
    exit();
}

// ✅ Handle item removal
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $conn->query("DELETE FROM cart WHERE cart_id = $cart_id AND customer_id = $customer_id");
    $_SESSION['message'] = "Item removed from cart!";
    header("Location: c_cart.php");
    exit();
}

// ✅ Fetch cart items with product details
$sql = "SELECT c.cart_id, c.quantity, p.name AS product_name, p.price, p.image_url
        FROM cart c
        JOIN product p ON c.product_id = p.product_id
        WHERE c.customer_id = $customer_id";
$result = $conn->query($sql);
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">🛒 My Cart</h2>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info text-center">
      <?php 
        echo $_SESSION['message']; 
        unset($_SESSION['message']); 
      ?>
    </div>
  <?php endif; ?>

  <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-warning">
          <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Total (₹)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $grand_total = 0;
          while ($row = $result->fetch_assoc()): 
              $total = $row['price'] * $row['quantity'];
              $grand_total += $total;
          ?>
          <tr>
            <td>
                  <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                  width="70" height="70" class="rounded">
            </td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
              <form method="post" class="d-flex justify-content-center">
                <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" class="form-control w-50 me-2">
                <button type="submit" name="update_cart" class="btn btn-sm btn-primary">Update</button>
              </form>
            </td>
            <td><?php echo $total; ?></td>
            <td>
              <a href="c_cart.php?remove=<?php echo $row['cart_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')">Remove</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot class="table-light">
          <tr>
            <th colspan="4" class="text-end">Grand Total</th>
            <th colspan="2">₹<?php echo $grand_total; ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="text-center mt-3">
      <a href="c_dashboard.php" class="btn btn-warning me-2">Continue Shopping</a>
      <a href="place_order.php" class="btn btn-success">Place Order</a>

    </div>
  <?php else: ?>
    <p class="text-center">Your cart is empty. <a href="c_dashboard.php">Shop now</a></p>
  <?php endif; ?>
</div>

<?php include("includes/c_footer.php"); ?>

