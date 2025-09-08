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
        header("Location: dashboard.php");
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
      background: linear-gradient(135deg, #1e3c72, #2a5298); /* ✅ First Blue Gradient */
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      background: #ffffff;
      padding: 40px 35px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 430px;
      animation: fadeInUp 0.8s ease-in-out;
    }
    .login-box h3 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: bold;
      color: #2a5298;
    }
    .form-control {
      border-radius: 8px;
      padding: 10px;
    }
    .btn-custom {
      background: #2a5298;
      color: #fff;
      border-radius: 8px;
      font-weight: bold;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background: #1e3c72;
      color: #fff;
    }
    .footer-text {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: #555;
    }
    @keyframes fadeInUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h3>🔐 Admin Login</h3>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">📧 Email</label>
        <input type="email" name="email" class="form-control shadow-sm" required>
      </div>
      <div class="mb-3">
        <label class="form-label">🔒 Password</label>
        <input type="password" name="password" class="form-control shadow-sm" required>
      </div>
      <button type="submit" class="btn btn-custom w-100 shadow-sm">Login</button>
    </form>
    <div class="footer-text">
      © <?php echo date("Y"); ?> DryFruit Store Admin
    </div>
  </div>
</body>
</html>
