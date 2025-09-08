<?php
include("includes/c_header.php");
include("includes/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['category_id'])) {
    echo "<div class='alert alert-danger text-center'>Category not selected.</div>";
    include("includes/c_footer.php");
    exit();
}

$category_id = (int)$_GET['category_id'];

// Get category name
$cat_sql = "SELECT name FROM category WHERE category_id = $category_id";
$cat_result = $conn->query($cat_sql);
$category_name = ($cat_result && $cat_result->num_rows > 0) 
    ? $cat_result->fetch_assoc()['name'] 
    : "Unknown Category";

// Fetch products for this category
$sql = "SELECT * FROM product WHERE category_id = $category_id AND status = 'active'";
$result = $conn->query($sql);
?>

<div class="container my-5">
  <h2 class="text-center mb-4"><?php echo htmlspecialchars($category_name); ?> Products</h2>
  
  <div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-3">
          <div class="card shadow-sm h-100">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                 class="card-img-top" 
                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                 style="height:200px; object-fit:cover;">
            <div class="card-body text-center">
              <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
              <p class="card-text">₹<?php echo number_format($row['price'], 2); ?></p>
              <a href="add_to_cart.php?id=<?php echo $row['product_id']; ?>" class="btn btn-success btn-sm">Add to Cart</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-warning text-center">No products found in this category.</div>
    <?php endif; ?>
  </div>
</div>

<?php include("includes/c_footer.php"); ?>


