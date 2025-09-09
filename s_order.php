<?php
session_start();
if (!isset($_SESSION['supplier_id'])) {
    header("Location: s_login.php");
    exit();
}

include("includes/db.php");
include("includes/s_header.php");

$supplier_id = $_SESSION['supplier_id'];

// Fetch all orders that include this supplier’s products
$sql = "
    SELECT 
        o.order_id, o.order_date, o.status, o.total_amount,
        oi.quantity, oi.price, 
        p.name AS product_name, p.image_url,
        c.name AS customer_name, c.email AS customer_email
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN product p ON oi.product_id = p.product_id
    JOIN customer c ON o.customer_id = c.customer_id
    WHERE p.supplier_id = ?
    ORDER BY o.order_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">📦 My Orders</h2>
    
    <?php if ($res->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Qty</th>
                        <th>Price (₹)</th>
                        <th>Total (₹)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php while($row = $res->fetch_assoc()): ?>
                        <tr>
                            <td>#<?= $row['order_id']; ?></td>
                            <td><?= $row['order_date']; ?></td>
                            <td>
                                <?= htmlspecialchars($row['customer_name']); ?><br>
                                <small class="text-muted"><?= $row['customer_email']; ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['product_name']); ?></td>
                            <td>
                                <?php if (!empty($row['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($row['image_url']); ?>" width="60" height="60" style="object-fit:cover;">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['quantity']; ?></td>
                            <td><?= number_format($row['price'], 2); ?></td>
                            <td><?= number_format($row['price'] * $row['quantity'], 2); ?></td>
                            <td>
                                <?php if ($row['status'] === 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($row['status'] === 'paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php elseif ($row['status'] === 'cancelled'): ?>
                                    <span class="badge bg-danger">Cancelled</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($row['status']); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No orders found for your products.</div>
    <?php endif; ?>
</div>

<?php include("includes/s_footer.php"); ?>
