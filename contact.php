<?php include("includes/header.php"); ?>
<h2 style="text-align:center; margin:20px;">Contact Us</h2>
<form method="post" action="">
  <input type="text" name="name" placeholder="Your Name" required>
  <input type="email" name="email" placeholder="Your Email" required>
  <textarea name="message" placeholder="Message" required></textarea>
  <button type="submit">Send</button>
</form>
<?php include("includes/footer.php"); ?>
