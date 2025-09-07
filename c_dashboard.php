<?php 
include("includes/c_header.php"); 
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-info text-center">
    <?php 
      echo $_SESSION['message']; 
      unset($_SESSION['message']); 
    ?>
  </div>
<?php endif; ?>


<div class="container mt-4">

  <!-- ✅ Modern Fade Image Slider -->
  <div id="dashboardCarousel" class="carousel slide carousel-fade mx-auto shadow rounded" data-bs-ride="carousel" data-bs-interval="4000" style="max-width:1000px;">
    <div class="carousel-inner rounded">

      <!-- Slide 1 -->
      <div class="carousel-item active">
        <a href="../index.php">
          <img src="../assets/images/slider1.jpg" class="d-block w-100" alt="Shop DryFruits" style="height:450px; object-fit:cover;">
          <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-3 py-2">
            <h5 class="mb-1">Shop Fresh DryFruits</h5>
            <small>Premium quality nuts & fruits from Valsad</small>
          </div>
        </a>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item">
        <a href="c_orders.php">
          <img src="../assets/images/slider2.jpg" class="d-block w-100" alt="My Orders" style="height:450px; object-fit:cover;">
          <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-3 py-2">
            <h5 class="mb-1">Track Your Orders</h5>
            <small>View history & manage active orders</small>
          </div>
        </a>
      </div>

      <!-- Slide 3 -->
      <div class="carousel-item">
        <a href="c_edit_profile.php">
          <img src="../assets/images/slider3.jpg" class="d-block w-100" alt="Profile" style="height:450px; object-fit:cover;">
          <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-3 py-2">
            <h5 class="mb-1">Update Your Profile</h5>
            <small>Manage account & preferences</small>
          </div>
        </a>
      </div>

    </div>

    <!-- ✅ Modern Left/Right Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#dashboardCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
    </button>
  </div>

  <!-- ✅ Welcome Section -->
  <h2 class="text-center mt-5">Welcome, <?php echo $_SESSION['name']; ?> 👋</h2>
  <p class="text-center">This is your <b>Customer Dashboard</b>. From here, you can view products, add to cart, and place orders.</p>

  <!-- ✅ Product Categories Section -->
  <section class="products mt-5">
    <h3 class="text-center mb-4">Explore Our Categories</h3>
    <div class="row g-4">

      <!-- Almonds -->
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <img src="../assets/images/1.jpeg" class="card-img-top" alt="Almonds" style="height:200px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title">Almonds</h5>
            <p class="card-text">₹550 / Kg</p>
            <a href="add_to_cart.php?product_id=1" class="btn btn-warning w-100">Add to Cart</a>
          </div>
        </div>
      </div>

      <!-- Cashews -->
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <img src="../assets/images/Cashewnuts-Plian-1.jpg" class="card-img-top" alt="Cashews" style="height:200px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title">Cashews</h5>
            <p class="card-text">₹720 / Kg</p>
            <a href="add_to_cart.php?product_id=2" class="btn btn-warning w-100">Add to Cart</a>
          </div>
        </div>
      </div>

      <!-- Pistachio -->
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <img src="../assets/images/Pistachio-640x427.webp" class="card-img-top" alt="Pistachio" style="height:200px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title">Pistachio</h5>
            <p class="card-text">₹950 / Kg</p>
            <a href="add_to_cart.php?product_id=3" class="btn btn-warning w-100">Add to Cart</a>
          </div>
        </div>
      </div>

      <!-- Raisins -->
      <div class="col-md-3">
        <div class="card shadow-sm h-100">
          <img src="../assets/images/dried-yellow-raisins-1000x1000.webp" class="card-img-top" alt="Raisins" style="height:200px; object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title">Raisins</h5>
            <p class="card-text">₹320 / Kg</p>
            <a href="add_to_cart.php?product_id=4" class="btn btn-warning w-100">Add to Cart</a>
          </div>
        </div>
      </div>

    </div>
  </section>

  <div class="text-center mt-4">
    <a href="../index.php" class="btn btn-warning me-2">Shop More Products</a>
    <a href="../logout.php" class="btn btn-danger">Logout</a>
  </div>

</div>

<?php include("includes/c_footer.php"); ?>
