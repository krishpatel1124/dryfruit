<?php
include("../includes/db.php");
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

// ✅ Fetch categories for dropdown
$cat_result = $conn->query("SELECT * FROM category ORDER BY name ASC");

// ✅ CREATE
if (isset($_POST['add_product'])) {
    $name   = $_POST['name'];
    $price  = $_POST['price'];
    $stock  = $_POST['stock'];
    $status = $_POST['status'];
    $desc   = $_POST['description'];
    $cat_id = $_POST['category_id'];

    // Handle image upload
    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName   = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = "uploads/" . $fileName; // store relative path
        }
    } else {
        $imagePath = "assets/images/default.png"; // fallback
    }

    $sql = "INSERT INTO product (name, price, stock, status, description, image_url, category_id) 
            VALUES ('$name', '$price', '$stock', '$status', '$desc', '$imagePath', '$cat_id')";
    $conn->query($sql);
}

// ✅ UPDATE
if (isset($_POST['update_product'])) {
    $id     = $_POST['product_id'];
    $name   = $_POST['name'];
    $price  = $_POST['price'];
    $stock  = $_POST['stock'];
    $status = $_POST['status'];
    $desc   = $_POST['description'];
    $cat_id = $_POST['category_id'];
    $imagePath = $_POST['current_image'];

    // If new image uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName   = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = "uploads/" . $fileName; // relative path
        }
    }

    $sql = "UPDATE product 
            SET name='$name', price='$price', stock='$stock', status='$status', description='$desc', 
                image_url='$imagePath', category_id='$cat_id'
            WHERE product_id=$id";
    $conn->query($sql);
}

// ✅ DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM product WHERE product_id=$id");
    header("Location: products.php");
    exit();
}

// Fetch all products with category name
$result = $conn->query("SELECT p.*, c.name AS category_name 
                        FROM product p 
                        LEFT JOIN category c ON p.category_id = c.category_id
                        ORDER BY p.product_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5">
  <h2 class="text-center mb-4">📦 Manage Products</h2>
  <a href="a_dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

  <!-- Add Product Form -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">Add New Product</div>
    <div class="card-body">
      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-3"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
          <div class="col-md-2"><input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required></div>
          <div class="col-md-2"><input type="number" name="stock" class="form-control" placeholder="Stock" required></div>
          <div class="col-md-2">
            <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="col-md-2">
            <select name="category_id" class="form-control" required>
              <option value="">Select Category</option>
              <?php 
              $cat_result->data_seek(0);
              while($cat = $cat_result->fetch_assoc()): ?>
                <option value="<?= $cat['category_id']; ?>"><?= $cat['name']; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3"><input type="file" name="image" class="form-control" accept="image/*"></div>
          <div class="col-md-12"><textarea name="description" class="form-control" placeholder="Description"></textarea></div>
        </div>
        <button type="submit" name="add_product" class="btn btn-success mt-3">Add Product</button>
      </form>
    </div>
  </div>

  <!-- Product Table -->
  <table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Status</th><th>Category</th><th>Description</th><th>Image</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <form method="post" enctype="multipart/form-data">
          <td><?= $row['product_id']; ?><input type="hidden" name="product_id" value="<?= $row['product_id']; ?>"></td>
          <td><input type="text" name="name" value="<?= $row['name']; ?>" class="form-control"></td>
          <td><input type="number" step="0.01" name="price" value="<?= $row['price']; ?>" class="form-control"></td>
          <td><input type="number" name="stock" value="<?= $row['stock']; ?>" class="form-control"></td>
          <td>
            <select name="status" class="form-control">
              <option value="active" <?= $row['status']=='active'?'selected':''; ?>>Active</option>
              <option value="inactive" <?= $row['status']=='inactive'?'selected':''; ?>>Inactive</option>
            </select>
          </td>
          <td>
            <select name="category_id" class="form-control">
              <?php 
              $cat_result->data_seek(0);
              while($cat = $cat_result->fetch_assoc()): ?>
                <option value="<?= $cat['category_id']; ?>" <?= $row['category_id']==$cat['category_id']?'selected':''; ?>>
                  <?= $cat['name']; ?>
                </option>
              <?php endwhile; ?>
            </select>
          </td>
          <td><textarea name="description" class="form-control"><?= $row['description']; ?></textarea></td>
          <td>
            <?php $imgPath = $row['image_url']; ?>
            <img src="../<?= $imgPath; ?>" width="60" class="mb-2"><br>
            <input type="file" name="image" class="form-control" accept="image/*">
            <input type="hidden" name="current_image" value="<?= $row['image_url']; ?>">
          </td>
          <td>
            <button type="submit" name="update_product" class="btn btn-primary btn-sm">Update</button>
            <a href="products.php?delete=<?= $row['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete product?')">Delete</a>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
