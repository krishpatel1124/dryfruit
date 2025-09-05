<?php 
include("includes/header.php"); 
include("includes/db.php"); 

$message_sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contact (name, email, message) 
            VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $message_sent = true;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2 style="text-align:center; margin:20px;">Contact Us</h2>

<?php if ($message_sent): ?>
    <p style="color: green; text-align:center; font-weight:bold;">✅ Your message has been sent successfully!</p>
<?php endif; ?>

<form method="post" action="">
  <input type="text" name="name" placeholder="Your Name" required>
  <input type="email" name="email" placeholder="Your Email" required>
  <textarea name="message" placeholder="Message" required></textarea>
  <button type="submit">Send</button>
</form>

<?php include("includes/footer.php"); ?>
