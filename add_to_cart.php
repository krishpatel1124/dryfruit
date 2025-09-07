<?php
session_start();
include("includes/db.php");

// ✅ Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// ✅ Check if product_id is passed
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // ✅ Check if product already exists in cart
    $check_sql = "SELECT * FROM cart WHERE customer_id = $customer_id AND product_id = $product_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // If already in cart → increase quantity
        $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE customer_id = $customer_id AND product_id = $product_id");
        $_SESSION['message'] = "Product quantity updated in your cart!";
    } else {
        // If not in cart → insert new record
        $conn->query("INSERT INTO cart (customer_id, product_id, quantity) VALUES ($customer_id, $product_id, 1)");
        $_SESSION['message'] = "Product added to your cart!";
    }
} else {
    $_SESSION['message'] = "Invalid request!";
}

// ✅ Redirect back to dashboard
header("Location: c_dashboard.php");
exit();
?>
