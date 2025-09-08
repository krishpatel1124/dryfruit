<?php 
// place_order.php
// Moves cart -> orders + order_items -> bill and empties the cart

// Enable mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include("includes/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = (int) $_SESSION['customer_id'];

try {
    // Start transaction
    $conn->begin_transaction();

    // 1) Fetch cart items with price + stock
    $stmt = $conn->prepare(
        "SELECT c.cart_id, c.product_id, c.quantity, p.price, p.stock
         FROM cart c
         JOIN product p ON c.product_id = p.product_id
         WHERE c.customer_id = ?"
    );
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $cartResult = $stmt->get_result();

    if (!$cartResult || $cartResult->num_rows == 0) {
        $_SESSION['message'] = "Your cart is empty.";
        $conn->rollback();
        header("Location: c_cart.php");
        exit();
    }

    // Collect items and total
    $items = [];
    $total_amount = 0.00;
    while ($r = $cartResult->fetch_assoc()) {
        $items[] = $r;
        $total_amount += ($r['price'] * $r['quantity']);
    }
    $stmt->close();

    // 2) Insert into orders table
    $status = 'Pending'; 
    $stmtOrder = $conn->prepare("INSERT INTO orders (customer_id, order_date, status, total_amount) VALUES (?, NOW(), ?, ?)");
    $stmtOrder->bind_param("isd", $customer_id, $status, $total_amount);
    $stmtOrder->execute();
    $order_id = $stmtOrder->insert_id;
    $stmtOrder->close();

    // 3) Insert each item into order_items + update stock
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmtUpdateStock = $conn->prepare("UPDATE product SET stock = stock - ? WHERE product_id = ? AND stock >= ?");
    foreach ($items as $it) {
        $prod_id = (int)$it['product_id'];
        $qty     = (int)$it['quantity'];
        $price   = (float)$it['price'];

        // insert order item
        $stmtItem->bind_param("iiid", $order_id, $prod_id, $qty, $price);
        $stmtItem->execute();

        // optional stock update
        $stmtUpdateStock->bind_param("iii", $qty, $prod_id, $qty);
        $stmtUpdateStock->execute();
    }
    $stmtItem->close();
    $stmtUpdateStock->close();

    // 4) Insert bill record
    $stmtBill = $conn->prepare("INSERT INTO bill (order_id, customer_id, total_amount) VALUES (?, ?, ?)");
    $stmtBill->bind_param("iid", $order_id, $customer_id, $total_amount);
    $stmtBill->execute();
    $stmtBill->close();

    // 5) Clear the cart
    $stmtDel = $conn->prepare("DELETE FROM cart WHERE customer_id = ?");
    $stmtDel->bind_param("i", $customer_id);
    $stmtDel->execute();
    $stmtDel->close();

    // Commit transaction
    $conn->commit();

    $_SESSION['message'] = "Order placed successfully! Your Order ID is #$order_id.";
    header("Location: c_order.php");
    exit();

} catch (Exception $e) {
    if ($conn->in_transaction) $conn->rollback();
    error_log("Place order failed: " . $e->getMessage());
    $_SESSION['message'] = "Failed to place order: " . $e->getMessage();
    header("Location: c_cart.php");
    exit();
}
?>
