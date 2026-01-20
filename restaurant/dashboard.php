<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>Restaurant Dashboard</h2>

<ul>
    <li><a href="view_prices.php">📊 View & Compare Prices</a></li>
</ul>