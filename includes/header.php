<!DOCTYPE html>
<html>
<head>
    <title>KitchenCart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="topbar">
    <div class="logo">🍽️ KitchenCart</div>
    <div class="nav-links">
        <a href="#">Dashboard</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'restaurant'): ?>
        <a href="../restaurant/view_prices.php">Prices</a>
        <a href="../restaurant/my_orders.php">Orders</a>
        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'vendor'): ?>
        <a href="../vendor/view_orders.php">Orders</a>
        <?php else: ?>
        <a href="#">Prices</a>
        <a href="#">Orders</a>
        <?php endif; ?>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>