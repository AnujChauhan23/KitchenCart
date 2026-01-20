<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "127.0.0.1";   // IMPORTANT
$user = "root";
$pass = "";            // NO password (correct for XAMPP)
$db   = "kitchencart_db";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

echo "Database connected successfully!";
?>