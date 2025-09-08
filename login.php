<?php 
include("includes/header.php"); 
include("includes/db.php"); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // First check in customer table
    $sql = "SELECT * FROM customer WHERE email='$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_id'] = $row['customer_id'];
            $_SESSION['name']        = $row['name'];
            $_SESSION['email']       = $row['email']; // ✅ store email
            header("Location: c_dashboard.php"); 
            exit();
        }
    }

    // If not found in customer, check supplier
    $sql2 = "SELECT * FROM supplier WHERE email='$email' LIMIT 1";
    $result2 = $conn->query($sql2);

    if ($result2->num_rows == 1) {
        $row2 = $result2->fetch_assoc();
        if (password_verify($password, $row2['password'])) {
            $_SESSION['supplier_id'] = $row2['supplier_id'];
            $_SESSION['name']        = $row2['name'];
            $_SESSION['email']       = $row2['email']; // ✅ store email
            header("Location: s_dashboard.php"); 
            exit();
        }
    }

    echo "<p style='color:red; text-align:center;'>❌ Invalid email or password</p>";
}
?>

<h2 style="text-align:center; margin:20px;">Login</h2>
<form method="post" action="">
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>

<?php include("includes/footer.php"); ?> 
