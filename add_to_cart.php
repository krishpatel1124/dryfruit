<?php
include("includes/db.php");
session_start();

if (!isset($_SESSION['customer_id'])) {
    // If user is not logged in, send them to login page
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $customer_id = $_SESSION['customer_id'];
    $product_id  = (int)$_GET['id'];

    // Check if product already exists in cart
    $check_sql = "SELECT * FROM cart WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $customer_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // ✅ Update quantity if already in cart
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE customer_id = ? AND product_id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("ii", $customer_id, $product_id);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // ✅ Insert new product into cart
        $insert_sql = "INSERT INTO cart (customer_id, product_id, quantity, added_date) VALUES (?, ?, 1, CURDATE())";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("ii", $customer_id, $product_id);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $stmt->close();

    // ✅ Redirect back to cart page
    header("Location: c_cart.php");
    exit();
} else {
    // If no product ID is provided
    header("Location: c_view_product.php");
    exit();
}
?>
