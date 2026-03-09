<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";
include "../includes/header.php";

$restaurant_id = $_SESSION['user_id'];

// Fetch Analytics
// 1. Total Spent (All orders, or we could limit to Delivered. Let's do all for simplicity)
$query_spent = "SELECT SUM(price * quantity) as total FROM orders WHERE restaurant_id = ?";
$stmt = mysqli_prepare($conn, $query_spent);
mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
mysqli_stmt_execute($stmt);
$res_spent = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
$total_spent = $res_spent ? $res_spent : 0;

// 2. Active Orders (Pending or Accepted)
$query_active = "SELECT count(*) as count FROM orders WHERE restaurant_id = ? AND status IN ('Pending', 'Accepted')";
$stmt2 = mysqli_prepare($conn, $query_active);
mysqli_stmt_bind_param($stmt2, "i", $restaurant_id);
mysqli_stmt_execute($stmt2);
$res_active = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2))['count'];

// 3. Total Orders Placed
$query_total = "SELECT count(*) as count FROM orders WHERE restaurant_id = ?";
$stmt3 = mysqli_prepare($conn, $query_total);
mysqli_stmt_bind_param($stmt3, "i", $restaurant_id);
mysqli_stmt_execute($stmt3);
$res_total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt3))['count'];

?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Restaurant Dashboard</h1>
        <p class="page-subtitle">Overview of your procurement process.</p>
    </div>

    <div class="dashboard-grid">
        <!-- Stat Card 1 -->
        <div class="card-elevated stat-card">
            <h3 class="stat-title">Total Spent</h3>
            <div class="stat-value">₹<?= number_format($total_spent, 2) ?></div>
            <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-wallet"></i> Lifetime expenditure</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="card-elevated stat-card">
            <h3 class="stat-title">Active Orders</h3>
            <div class="stat-value"><?= $res_active ?></div>
            <p class="trend stable" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-package"></i> Pending delivery</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="card-elevated stat-card">
            <h3 class="stat-title">Total Orders</h3>
            <div class="stat-value"><?= $res_total ?></div>
            <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-shopping-bag"></i> Orders placed</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="page-header" style="margin-top: 2rem;">
        <h2 class="page-title" style="font-size:1.25rem;">Quick Actions</h2>
    </div>
    <div class="card-elevated" style="display:flex; gap:1rem;">
        <a href="view_prices.php" class="btn-primary"><i class="ph ph-tag" style="margin-right:0.5rem;"></i> Compare Prices</a>
        <a href="my_orders.php" class="btn-primary" style="background-color:hsl(var(--secondary)); color:hsl(var(--secondary-foreground));"><i class="ph ph-clock-counter-clockwise" style="margin-right:0.5rem;"></i> Order History</a>
    </div>

</div>
</main>
</div>
</body>
</html>