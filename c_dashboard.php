<?php 
include("includes/c_header.php"); 
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!-- ================== Slider ================== -->
<div id="categorySlider" class="carousel slide container mt-4" data-bs-ride="carousel">
  <div class="carousel-inner rounded shadow">
    <div class="carousel-item active">
      <img src="assets/images/dryfruitsandnuts.jpg" class="d-block w-100" style="height:400px; object-fit:cover;" alt="Almonds">
      <div class="carousel-caption d-none d-md-block">
        <h5>Premium Almonds</h5>
        <p>Fresh from Valsad</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/OIP.webp" class="d-block w-100" style="height:400px; object-fit:cover;" alt="Cashews">
      <div class="carousel-caption d-none d-md-block">
        <h5>Crunchy Cashews</h5>
        <p>Best quality handpicked cashews</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="assets/images/Pistachio-640x427.webp" class="d-block w-100" style="height:400px; object-fit:cover;" alt="Raisins">
      <div class="carousel-caption d-none d-md-block">
        <h5>Sweet Raisins</h5>
        <p>Natural & delicious raisins</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#categorySlider" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#categorySlider" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- ================== Categories Section ================== -->
<div class="container my-5">
  <h2 class="text-center mb-4">Explore Categories</h2>
  <div class="row g-4">
    <!-- Almonds -->
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <img src="assets/images/1.jpeg" class="card-img-top" alt="Almonds" style="height:200px; object-fit:cover;">
        <div class="card-body text-center">
          <h5 class="card-title">Almonds</h5>
          <a href="c_product.php?category_id=1" class="btn btn-primary">View Products</a>
        </div>
      </div>
    </div>
    <!-- Cashews -->
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <img src="assets/images/Cashewnuts-Plian-1.jpg" class="card-img-top" alt="Cashews" style="height:200px; object-fit:cover;">
        <div class="card-body text-center">
          <h5 class="card-title">Cashews</h5>
          <a href="c_product.php?category_id=2" class="btn btn-primary">View Products</a>
        </div>
      </div>
    </div>
    <!-- Raisins -->
    <div class="col-md-3">
      <div class="card shadow-sm h-100">
        <img src="assets/images/dried-yellow-raisins-1000x1000.webp" class="card-img-top" alt="Raisins" style="height:200px; object-fit:cover;">
        <div class="card-body text-center">
          <h5 class="card-title">Raisins</h5>
          <a href="c_product.php?category_id=3" class="btn btn-primary">View Products</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("includes/c_footer.php"); ?>
