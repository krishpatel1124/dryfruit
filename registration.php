<?php 
include("includes/header.php"); 
include("includes/db.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $conn->real_escape_string($_POST['name']);
    $email    = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure hash
    $role     = $_POST['role'];

    if ($role == "customer") {
        $sql = "INSERT INTO customer (name, email, password) VALUES ('$name', '$email', '$password')";
    } elseif ($role == "supplier") {
        $sql = "INSERT INTO supplier (name, email, password) VALUES ('$name', '$email', '$password')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green; text-align:center;'>✅ Registration successful! You can now <a href='login.php'>Login</a>.</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<h2 style="text-align:center; margin:20px;">Register</h2>
<form method="post" action="">
  <input type="text" name="name" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <select name="role" required>
    <option value="">Select Role</option>
    <option value="customer">Customer</option>
    <option value="supplier">Supplier</option>
  </select>
  <button type="submit">Register</button>
</form>

<?php include("includes/footer.php"); ?>
