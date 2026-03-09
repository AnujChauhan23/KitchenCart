<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KitchenCart platform</title>
    <!-- Include the central CSS system -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Phosphor Icons via CDN for a modern icon set -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<div class="app-container">
    <!-- Fixed Sidebar Navigation -->
    <aside class="sidebar">
        <a href="<?= (isset($_SESSION['role']) && $_SESSION['role'] == 'vendor') ? '../vendor/dashboard.php' : '../restaurant/dashboard.php'; ?>" class="sidebar-logo" style="text-decoration:none;">
            <i class="ph-fill ph-storefront"></i> KitchenCart
        </a>
        
        <nav class="nav-links">
            <a href="<?= (isset($_SESSION['role']) && $_SESSION['role'] == 'vendor') ? '../vendor/dashboard.php' : '../restaurant/dashboard.php'; ?>" class="<?= (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'restaurant'): ?>
                <a href="../restaurant/view_prices.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'view_prices.php') ? 'active' : ''; ?>">
                    <i class="ph ph-tag"></i> Prices
                </a>
                <a href="../restaurant/my_orders.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'my_orders.php') ? 'active' : ''; ?>">
                    <i class="ph ph-shopping-bag"></i> Orders
                </a>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'vendor'): ?>
                <a href="../vendor/view_orders.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'view_orders.php') ? 'active' : ''; ?>">
                    <i class="ph ph-shopping-bag"></i> Orders
                </a>
            <?php endif; ?>
            
            <a href="../auth/logout.php" class="logout-link">
                <i class="ph ph-sign-out"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Dynamic Content Area -->
    <main class="main-content">