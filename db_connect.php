<?php
$host = 'localhost';
$db   = 'college';
$user = 'root';
$pass = ''; // adjust based on your setup

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
