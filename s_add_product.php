<?php
// s_add_product.php
session_start();
include("includes/db.php");

// Require supplier login
if (!isset($_SESSION['supplier_id'])) {
    header("Location: s_login.php");
    exit();
}

$supplier_id = (int)$_SESSION['supplier_id'];

// --- Fetch categories for the dropdown ---
$cat_result = $conn->query("SELECT category_id, name FROM category ORDER BY name ASC");

// Helpers
function alert($type, $msg) {
    echo '<div class="alert alert-' . $type . ' text-center">' . $msg . '</div>';
}

$success = null;
$error   = null;

// --- Handle Add Product ---
if (isset($_POST['add_product'])) {
    // Basic validation
    $name   = trim($_POST['name'] ?? '');
    $price  = trim($_POST['price'] ?? '');
    $stock  = trim($_POST['stock'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $cat_id = (int)($_POST['category_id'] ?? 0);

    if ($name === '' || $price === '' || $stock === '' || $cat_id === 0) {
        $error = "Please fill all required fields.";
    } else {
        // ---------- Image Upload ----------
        // We will save RELATIVE path in DB, e.g. "uploads/1699999999_myimg.jpg"
        // Filesystem (where to move file) -> project_root/uploads/
        // This file is at project root ("dryfruit/s_add_product.php"), so this works:
        $fsUploadDir  = __DIR__ . "/uploads/";
        $webUploadDir = "uploads/"; // what we store in DB

        if (!is_dir($fsUploadDir)) {
            @mkdir($fsUploadDir, 0777, true);
        }

        $imagePath = "assets/images/default.png"; // default if no file chosen

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileErr = $_FILES['image']['error'];

            if ($fileErr === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['image']['tmp_name'];
                $orig    = basename($_FILES['image']['name']);

                // Validate extension & mime
                $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                $allowedExt = ['jpg','jpeg','png','webp','gif'];
                if (!in_array($ext, $allowedExt, true)) {
                    $error = "Only JPG, JPEG, PNG, WEBP, GIF images are allowed.";
                } else {
                    // Extra safety: basic mime check
                    $finfo = @finfo_open(FILEINFO_MIME_TYPE);
                    $mime  = $finfo ? finfo_file($finfo, $tmpName) : '';
                    if ($finfo) finfo_close($finfo);
                    $allowedMime = ['image/jpeg','image/png','image/webp','image/gif'];
                    if ($mime && !in_array($mime, $allowedMime, true)) {
                        $error = "Invalid image file type.";
                    } else {
                        // Build unique filename
                        $newName = time() . "_" . preg_replace('/[^A-Za-z0-9_\.-]/', '_', $orig);
                        $destFS  = $fsUploadDir . $newName;      // filesystem path
                        $destWEB = $webUploadDir . $newName;     // relative web path for DB

                        if (move_uploaded_file($tmpName, $destFS)) {
                            $imagePath = $destWEB;
                        } else {
                            $error = "Failed to move uploaded file. Check folder permissions for /uploads.";
                        }
                    }
                }
            } else {
                // Common upload errors
                $errText = match ($fileErr) {
                    UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => "Uploaded file is too large.",
                    UPLOAD_ERR_PARTIAL => "File only partially uploaded. Please retry.",
                    UPLOAD_ERR_NO_TMP_DIR => "Server missing a temp folder.",
                    UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
                    UPLOAD_ERR_EXTENSION => "Upload stopped by a PHP extension.",
                    default => "Unknown upload error code: $fileErr"
                };
                $error = "Image upload error: $errText";
            }
        }

        // If no upload error, insert product
        if (!$error) {
            $status = 'Pending'; // supplier-submitted -> admin approves
            $stmt = $conn->prepare("
                INSERT INTO product
                    (name, price, stock, status, description, image_url, category_id, supplier_id)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "sdisssii",
                $name,
                $price,
                $stock,
                $status,
                $desc,
                $imagePath,
                $cat_id,
                $supplier_id
            );

            if ($stmt->execute()) {
                $success = "✅ Product submitted for admin approval!";
                // Reset form fields after success
                $name = $price = $stock = $desc = "";
                $cat_id = 0;
            } else {
                $error = "DB Error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier - Add Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f7f7fb; }
    .card { border:0; box-shadow: 0 8px 24px rgba(0,0,0,.08); border-radius:16px; }
    .card-header { border-top-left-radius:16px; border-top-right-radius:16px; }
  </style>
</head>
<body class="bg-light">
<div class="container my-5" style="max-width: 980px;">
  <h2 class="text-center mb-4">➕ Add New Product</h2>
  <div class="d-flex gap-2 mb-3">
    <a href="s_dashboard.php" class="btn btn-secondary">⬅ Dashboard</a>
    <a href="s_products.php" class="btn btn-primary">📦 My Products</a>
  </div>

  <?php if ($success) alert('success', $success); ?>
  <?php if ($error)   alert('danger',  $error); ?>

  <div class="card">
    <div class="card-header bg-success text-white">New Product</div>
    <div class="card-body">
      <!-- enctype is required for file upload -->
      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Product Name *</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($name ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Price (₹) *</label>
            <input type="number" step="0.01" name="price" class="form-control" required value="<?= htmlspecialchars($price ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Stock *</label>
            <input type="number" name="stock" class="form-control" required value="<?= htmlspecialchars($stock ?? '') ?>">
          </div>

          <div class="col-md-6">
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-select" required>
              <option value="">Select Category</option>
              <?php if ($cat_result && $cat_result->num_rows): ?>
                <?php while($cat = $cat_result->fetch_assoc()): ?>
                  <option value="<?= $cat['category_id']; ?>" <?= (isset($cat_id) && (int)$cat_id === (int)$cat['category_id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($cat['name']); ?>
                  </option>
                <?php endwhile; ?>
              <?php endif; ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <div class="form-text">Allowed: JPG, PNG, WEBP, GIF</div>
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($desc ?? '') ?></textarea>
          </div>
        </div>

        <button type="submit" name="add_product" class="btn btn-success mt-3">Submit for Approval</button>
      </form>
    </div>
  </div>

  <div class="mt-3">
    <small class="text-muted">
      If the image still doesn't appear: ensure the folder <code>/uploads</code> exists under your project root (<code>dryfruit/uploads/</code>)
      and PHP can write to it (Windows: no special chmod; Linux/Mac: 755 or 775).
      Also check <code>upload_max_filesize</code> and <code>post_max_size</code> in php.ini are large enough (e.g., 8M+).
    </small>
  </div>
</div>
</body>
</html>
