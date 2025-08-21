<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DryFruit Store</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
  <!-- Left side logo with dropdown -->
  <div class="dropdown">
    <button class="dropbtn">DryFruit<span>Store</span> ▼</button>
    <div class="dropdown-content">
      <a href="index.php">Home</a>
      <a href="aboutus.php">About Us</a>
      <a href="contact.php">Contact Us</a>
      <a href="registration.php">Register</a>
    </div>
  </div>

  <!-- Right side login/logout -->
  <nav>
    <?php if(isset($_SESSION['user'])): ?>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>
<main>
