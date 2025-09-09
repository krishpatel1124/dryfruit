<?php
session_start();
if (!isset($_SESSION['supplier_id'])) {
    header("Location: login.php");
    exit();
}

include("includes/db.php");
include("includes/s_header.php");

// Handle delete product
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $supplier_id = $_SESSION['supplier_id'];

    // Ensure supplier can only delete their own product
    $del_sql = "DELETE FROM product WHERE product_id = $delete_id AND supplier_id = $supplier_id";
    $conn->query($del_sql);
    header("Location: s_products.php");
    exit();
}

// Fetch supplier products with category name
$supplier_id = $_SESSION['supplier_id'];
$sql = "SELECT p.*, c.name AS category_name 
        FROM product p 
        LEFT JOIN category c ON p.category_id = c.category_id
        WHERE p.supplier_id = $supplier_id
        ORDER BY p.product_id DESC";
$res = $conn->query($sql);
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 My Products</h2>
        <a href="s_add_product.php" class="btn btn-primary">➕ Add New Product</a>
    </div>

    <?php if ($res->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Approval Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td>
                            <?php if (!empty($row['image_url'])): ?>
                                <img src="<?= htmlspecialchars($row['image_url']); ?>" alt="Product" width="60" height="60" style="object-fit:cover;">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['category_name']); ?></td>
                        <td>₹<?= number_format($row['price'], 2); ?></td>
                        <td><?= $row['stock']; ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($row['status'] === 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($row['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $row['approval_date'] ?? '-'; ?></td>
                        <td>
                            <a href="s_edit_product.php?id=<?= $row['product_id']; ?>" class="btn btn-sm btn-info mb-1">Edit</a>
                            <a href="s_products.php?delete_id=<?= $row['product_id']; ?>" 
                               class="btn btn-sm btn-danger mb-1" 
                               onclick="return confirm('Are you sure to delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">You have not added any products yet.</div>
    <?php endif; ?>
</div>

<?php include("includes/s_footer.php"); ?>
