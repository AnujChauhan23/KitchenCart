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

<ul>
    <li><a href="add_product.php">➕ Add Product</a></li>
    <li><a href="update_price.php">💰 Update Daily Price</a></li>
    <li><a href="../auth/logout.php">🚪 Logout</a></li>
</ul>

</body>
</html>