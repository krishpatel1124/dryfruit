<?php
session_start();
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

include("includes/db.php");
include("includes/s_header.php");

$supplier_id = $_SESSION['supplier_id'];

// Get product ID from URL
if (!isset($_GET['id'])) {
    header("Location: s_products.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product details (only if belongs to this supplier)
$sql = "SELECT * FROM product WHERE product_id = $product_id AND supplier_id = $supplier_id LIMIT 1";
$res = $conn->query($sql);
if ($res->num_rows == 0) {
    header("Location: s_products.php");
    exit();
}
$product = $res->fetch_assoc();

// Fetch categories for dropdown
$categories = [];
$cat_sql = "SELECT category_id, name FROM category ORDER BY name ASC";
$cat_res = $conn->query($cat_sql);
if ($cat_res->num_rows > 0) {
    while ($row = $cat_res->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    // Handle image upload if new image is selected
    $image_url = $product['image_url']; // keep old image by default
    if (isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $img_name = time() . '_' . $_FILES['image']['name'];
        $img_tmp = $_FILES['image']['tmp_name'];
        $img_folder = 'uploads/products/' . $img_name;

        if (!is_dir('uploads/products')) {
            mkdir('uploads/products', 0777, true);
        }

        if (move_uploaded_file($img_tmp, $img_folder)) {
            // Delete old image if exists
            if ($product['image_url'] && file_exists('uploads/products/'.$product['image_url'])) {
                unlink('uploads/products/'.$product['image_url']);
            }
            $image_url = $img_name;
        } else {
            $error = "Failed to upload image.";
        }
    }

    // Update product
    if (!isset($error)) {
        $update_sql = "UPDATE product SET 
                        name = '$name',
                        description = '$description',
                        category_id = $category_id,
                        price = $price,
                        stock = $stock,
                        image_url = '$image_url'
                       WHERE product_id = $product_id AND supplier_id = $supplier_id";

        if ($conn->query($update_sql)) {
            $success = "Product updated successfully!";
            // Refresh product info
            $sql = "SELECT * FROM product WHERE product_id = $product_id AND supplier_id = $supplier_id LIMIT 1";
            $res = $conn->query($sql);
            $product = $res->fetch_assoc();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✏️ Edit Product</h2>
        <div>
            <a href="s_dashboard.php" class="btn btn-secondary me-2">← Back to Dashboard</a>
            <a href="s_products.php" class="btn btn-primary">📦 Manage Products</a>
        </div>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if($product['image_url'] && file_exists('uploads/products/'.$product['image_url'])): ?>
                    <img src="uploads/products/<?= $product['image_url'] ?>" alt="Product" width="100" class="mt-2">
                <?php endif; ?>
            </div>

            <button type="submit" name="submit" class="btn btn-success">Update Product</button>
        </form>
    </div>
</div>

<?php include("includes/s_footer.php"); ?>
