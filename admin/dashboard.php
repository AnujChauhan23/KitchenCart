<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

// --- Analytics ---
$total_users    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM users"))['c'];
$total_vendors  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM vendors"))['c'];
$total_orders   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM orders"))['c'];
$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM products"))['c'];
$pending_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM orders WHERE status='Pending'"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — KitchenCart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <i class="ph-fill ph-storefront"></i> KitchenCart
        </div>
        <nav class="nav-links">
            <a href="dashboard.php" class="active">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="manage_users.php">
                <i class="ph ph-users"></i> Users
            </a>
            <a href="manage_vendors.php">
                <i class="ph ph-storefront"></i> Vendors
            </a>
            <a href="manage_orders.php">
                <i class="ph ph-shopping-bag"></i> Orders
            </a>
            <a href="../auth/logout.php" class="logout-link">
                <i class="ph ph-sign-out"></i> Logout
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Admin Dashboard</h1>
            <p class="page-subtitle">Platform overview and management controls.</p>
        </div>

        <!-- Stats Grid -->
        <div class="dashboard-grid">
            <div class="card-elevated stat-card">
                <h3 class="stat-title">Total Users</h3>
                <div class="stat-value"><?= $total_users ?></div>
                <p class="trend stable" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-users"></i> Registered accounts</p>
            </div>
            <div class="card-elevated stat-card">
                <h3 class="stat-title">Active Vendors</h3>
                <div class="stat-value"><?= $total_vendors ?></div>
                <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-storefront"></i> On the platform</p>
            </div>
            <div class="card-elevated stat-card">
                <h3 class="stat-title">Total Orders</h3>
                <div class="stat-value"><?= $total_orders ?></div>
                <p class="trend stable" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-shopping-bag"></i> Lifetime orders</p>
            </div>
            <div class="card-elevated stat-card">
                <h3 class="stat-title">Pending Orders</h3>
                <div class="stat-value"><?= $pending_orders ?></div>
                <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-clock"></i> Awaiting action</p>
            </div>
            <div class="card-elevated stat-card">
                <h3 class="stat-title">Products Listed</h3>
                <div class="stat-value"><?= $total_products ?></div>
                <p class="trend up" style="margin-top:0.5rem;font-size:0.75rem;"><i class="ph ph-package"></i> In catalog</p>
            </div>
        </div>

        <!-- Users Table -->
        <div class="page-header" style="margin-top:2.5rem;">
            <h2 class="page-title" style="font-size:1.25rem;">All Users</h2>
            <p class="page-subtitle">All registered accounts across all roles.</p>
        </div>
        <div class="card-elevated">
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $users = mysqli_query($conn, "SELECT user_id, name, email, role, status FROM users ORDER BY user_id ASC");
                    while ($u = mysqli_fetch_assoc($users)):
                        $roleColors = [
                            'admin'      => 'background-color:hsl(220 50% 92%); color:hsl(220 50% 30%);',
                            'vendor'     => 'background-color:hsl(var(--primary)/0.1); color:hsl(var(--primary));',
                            'restaurant' => 'background-color:hsl(35 85% 55%/0.15); color:hsl(35 85% 35%);',
                        ];
                        $roleStyle = $roleColors[$u['role']] ?? '';
                        $statusBadge = $u['status']
                            ? "<span class='badge' style='background-color:hsl(150 40% 92%);color:hsl(150 45% 30%);border:1px solid hsl(150 30% 80%);'>Active</span>"
                            : "<span class='badge' style='background-color:hsl(0 40% 95%);color:hsl(0 50% 40%);border:1px solid hsl(0 30% 85%);'>Inactive</span>";
                    ?>
                    <tr>
                        <td>#<?= $u['user_id'] ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge" style="<?= $roleStyle ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td><?= $statusBadge ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="page-header" style="margin-top:2.5rem;">
            <h2 class="page-title" style="font-size:1.25rem;">Recent Orders</h2>
            <p class="page-subtitle">Latest orders across the entire platform.</p>
        </div>
        <div class="card-elevated">
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Restaurant</th>
                        <th>Vendor</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $orders = mysqli_query($conn, "
                        SELECT o.order_id, r.name AS restaurant_name, v.vendor_name, p.product_name,
                               o.quantity, o.price, o.order_date, o.status
                        FROM orders o
                        JOIN users r ON o.restaurant_id = r.user_id
                        JOIN vendors v ON o.vendor_id = v.vendor_id
                        JOIN products p ON o.product_id = p.product_id
                        ORDER BY o.order_date DESC
                        LIMIT 20
                    ");
                    if ($orders && mysqli_num_rows($orders) > 0):
                        while ($o = mysqli_fetch_assoc($orders)):
                            $sc = 'status-' . strtolower($o['status']);
                    ?>
                    <tr>
                        <td>#<?= $o['order_id'] ?></td>
                        <td><?= htmlspecialchars($o['restaurant_name']) ?></td>
                        <td><?= htmlspecialchars($o['vendor_name']) ?></td>
                        <td><?= htmlspecialchars($o['product_name']) ?></td>
                        <td><?= $o['quantity'] ?></td>
                        <td>₹<?= $o['price'] ?></td>
                        <td><?= date('d M Y', strtotime($o['order_date'])) ?></td>
                        <td><span class="badge <?= $sc ?>"><?= $o['status'] ?></span></td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr><td colspan="8" style="text-align:center; color:hsl(var(--muted-foreground));">No orders yet.</td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

    </main>
</div>

</body>
</html>