<?php
include("../includes/db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['admin_email'] = $email; // Only store email in session
        header("Location: a_dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - DryFruit Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
    }
    .login-box {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
    }
    .login-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #1e3c72;
    }
    .btn-login {
      background: #1e3c72;
      color: #fff;
      font-weight: bold;
      width: 100%;
    }
    .btn-login:hover {
      background: #2a5298;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>🔑 Admin Login</h2>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
      </div>
      <button type="submit" class="btn btn-login">Login</button>
    </form>
  </div>
</body>
</html>
