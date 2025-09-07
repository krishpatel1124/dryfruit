<?php 
include("includes/s_header.php"); 
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}
?>

<h2 style="text-align:center; margin:20px;">Welcome, <?php echo $_SESSION['name']; ?> 🙌</h2>
<p style="text-align:center;">This is your <b>Supplier Dashboard</b>. From here, you can add products, manage stock, and check orders.</p>

<div style="text-align:center; margin-top:20px;">
    <a href="add_product.php" class="hero-btn">Add New Product</a>
    <a href="logout.php" class="hero-btn">Logout</a>
</div>

<?php include("includes/s_footer.php"); ?>
