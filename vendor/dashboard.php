<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard - KitchenCart</title>
</head>
<body>

<h2>Welcome Vendor 👋</h2>
<p>Manage products, prices, and incoming orders</p>
<ul>
    <li><a href="add_product.php">➕ Add Product</a></li>
    <li><a href="update_price.php">💰 Update Daily Price</a></li>
    <li><a href="view_orders.php">View Orders</a></li>
    <li><a href="../auth/logout.php">🚪 Logout</a></li>
</ul>
<br><br>
<a href="../auth/logout.php">Logout</a>
</body>
</html>