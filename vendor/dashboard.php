<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";
include "../includes/header.php";

$vendor_id = $_SESSION['user_id'];

// Fetch Analytics
// 1. Total Pending Orders
$query_pending = "SELECT count(*) as count FROM orders WHERE vendor_id = ? AND status = 'Pending'";
$stmt = mysqli_prepare($conn, $query_pending);
mysqli_stmt_bind_param($stmt, "i", $vendor_id);
mysqli_stmt_execute($stmt);
$res_pending = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['count'];

// 2. Total Revenue from Delivered Orders
$query_revenue = "SELECT SUM(price * quantity) as total FROM orders WHERE vendor_id = ? AND status = 'Delivered'";
$stmt2 = mysqli_prepare($conn, $query_revenue);
mysqli_stmt_bind_param($stmt2, "i", $vendor_id);
mysqli_stmt_execute($stmt2);
$res_rev = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2))['total'];
$total_revenue = $res_rev ? $res_rev : 0;

// 3. Products Listed
$query_products = "SELECT count(DISTINCT product_id) as count FROM products WHERE vendor_id = ?";
$stmt3 = mysqli_prepare($conn, $query_products);
mysqli_stmt_bind_param($stmt3, "i", $vendor_id);
mysqli_stmt_execute($stmt3);
$res_products = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt3))['count'];

?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">Vendor Dashboard</h1>
        <p class="page-subtitle">Overview of your store's performance.</p>
    </div>

    <div class="dashboard-grid">
        <!-- Stat Card 1 -->
        <div class="card-elevated stat-card">
            <h3 class="stat-title">Pending Orders</h3>
            <div class="stat-value"><?= $res_pending ?></div>
            <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-clock"></i> Requires attention</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="card-elevated stat-card">
            <h3 class="stat-title">Total Revenue</h3>
            <div class="stat-value">₹<?= number_format($total_revenue, 2) ?></div>
            <p class="trend stable" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-check-circle"></i> From delivered orders</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="card-elevated stat-card">
            <h3 class="stat-title">Products Listed</h3>
            <div class="stat-value"><?= $res_products ?></div>
            <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-package"></i> Active in catalog</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="page-header" style="margin-top: 2rem;">
        <h2 class="page-title" style="font-size:1.25rem;">Quick Actions</h2>
    </div>
    <div class="card-elevated" style="display:flex; gap:1rem;">
        <a href="add_product.php" class="btn-primary"><i class="ph ph-plus" style="margin-right:0.5rem;"></i> Add Product</a>
        <a href="update_price.php" class="btn-primary"><i class="ph ph-currency-inr" style="margin-right:0.5rem;"></i> Update Prices</a>
        <a href="view_orders.php" class="btn-primary" style="background-color:hsl(var(--secondary)); color:hsl(var(--secondary-foreground));"><i class="ph ph-list-dashes" style="margin-right:0.5rem;"></i> View All Orders</a>
    </div>

</div>
</main>
</div>
</body>
</html>