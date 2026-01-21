<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>Restaurant Dashboard</h2>
<p>Compare prices and place orders</p>
<ul>
    <li><a href="view_prices.php">📊 View & Compare Prices</a></li>
<br><br>
<a href="../auth/logout.php">Logout</a>
</ul>