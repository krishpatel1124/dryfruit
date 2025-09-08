<?php
include("includes/c_header.php");
include("includes/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all active products
$sql = "SELECT * FROM product WHERE status = 'active'";
$result = $conn->query($sql);
?>

<div class="container my-5">
  <h2 class="text-center mb-4">🌰 Explore Our Fresh Dry Fruits</h2>

  <div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-3">
          <div class="card shadow-lg border-0 h-100">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                 class="card-img-top" 
                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                 style="height:220px; object-fit:cover;">
            <div class="card-body text-center">
              <h5 class="card-title text-dark fw-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
              <p class="card-text text-muted small"><?php echo htmlspecialchars($row['description']); ?></p>
              <p class="fw-bold text-success fs-5">₹<?php echo number_format($row['price'], 2); ?></p>
              <a href="add_to_cart.php?id=<?php echo $row['product_id']; ?>" 
                 class="btn btn-warning w-100 fw-bold">🛒 Add to Cart</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-warning text-center">No products available right now.</div>
    <?php endif; ?>
  </div>
</div>

<?php include("includes/c_footer.php"); ?>
