<?php
$host = "localhost";
$user = "root";      // change if you set a password in phpMyAdmin
$pass = "";          // put your MySQL password if not empty
$db   = "dryfruit_db"; // your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
