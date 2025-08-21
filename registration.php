<?php include("includes/header.php"); ?>
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
