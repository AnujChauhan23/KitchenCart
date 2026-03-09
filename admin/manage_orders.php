<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

// Optional filter by status
$filter = isset($_GET['status']) && in_array($_GET['status'], ['Pending','Accepted','Delivered']) 
          ? $_GET['status'] 
          : '';

$where = $filter ? "WHERE o.status = '$filter'" : '';

$orders = mysqli_query($conn, "
    SELECT o.order_id, r.name AS restaurant_name, v.vendor_name,
           p.product_name, o.quantity, o.price,
           (o.quantity * o.price) AS total,
           o.order_date, o.status
    FROM orders o
    JOIN users r    ON o.restaurant_id = r.user_id
    JOIN vendors v  ON o.vendor_id = v.vendor_id
    JOIN products p ON o.product_id = p.product_id
    $where
    ORDER BY o.order_date DESC
");

// Counts for quick filters
$counts = [];
foreach (['Pending', 'Accepted', 'Delivered'] as $s) {
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM orders WHERE status='$s'"));
    $counts[$s] = $r['c'];
}
$total_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as c FROM orders"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders — KitchenCart Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <a href="dashboard.php" class="sidebar-logo" style="text-decoration:none;"><i class="ph-fill ph-storefront"></i> KitchenCart</a>
        <nav class="nav-links">
            <a href="dashboard.php"><i class="ph ph-squares-four"></i> Dashboard</a>
            <a href="manage_users.php"><i class="ph ph-users"></i> Users</a>
            <a href="manage_vendors.php"><i class="ph ph-storefront"></i> Vendors</a>
            <a href="manage_orders.php" class="active"><i class="ph ph-shopping-bag"></i> Orders</a>
            <a href="../auth/logout.php" class="logout-link"><i class="ph ph-sign-out"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Orders</h1>
            <p class="page-subtitle">Platform-wide order overview and status tracking.</p>
        </div>

        <!-- Filter Pills -->
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:1.5rem;">
            <a href="manage_orders.php" class="badge <?= !$filter ? 'status-accepted' : ''; ?>" 
               style="padding:0.4rem 1rem; font-size:0.8rem; cursor:pointer; text-decoration:none; border:1px solid hsl(var(--border));">
               All (<?= $total_count ?>)
            </a>
            <a href="?status=Pending" class="badge status-pending" 
               style="padding:0.4rem 1rem; font-size:0.8rem; cursor:pointer; text-decoration:none; <?= $filter=='Pending' ? 'outline:2px solid hsl(var(--warning));' : '' ?>">
               Pending (<?= $counts['Pending'] ?>)
            </a>
            <a href="?status=Accepted" class="badge status-accepted" 
               style="padding:0.4rem 1rem; font-size:0.8rem; cursor:pointer; text-decoration:none; <?= $filter=='Accepted' ? 'outline:2px solid hsl(var(--primary));' : '' ?>">
               Accepted (<?= $counts['Accepted'] ?>)
            </a>
            <a href="?status=Delivered" class="badge status-delivered" 
               style="padding:0.4rem 1rem; font-size:0.8rem; cursor:pointer; text-decoration:none; <?= $filter=='Delivered' ? 'outline:2px solid hsl(var(--success));' : '' ?>">
               Delivered (<?= $counts['Delivered'] ?>)
            </a>
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
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php if ($orders && mysqli_num_rows($orders) > 0):
                        while ($o = mysqli_fetch_assoc($orders)):
                            $sc = 'status-' . strtolower($o['status']);
                    ?>
                    <tr>
                        <td>#<?= $o['order_id'] ?></td>
                        <td><?= htmlspecialchars($o['restaurant_name']) ?></td>
                        <td><?= htmlspecialchars($o['vendor_name']) ?></td>
                        <td><?= htmlspecialchars($o['product_name']) ?></td>
                        <td><?= $o['quantity'] ?></td>
                        <td>₹<?= number_format($o['price'], 2) ?></td>
                        <td>₹<?= number_format($o['total'], 2) ?></td>
                        <td><?= date('d M Y', strtotime($o['order_date'])) ?></td>
                        <td><span class="badge <?= $sc ?>"><?= $o['status'] ?></span></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center; color:hsl(var(--muted-foreground)); padding:2rem;">
                            No orders found<?= $filter ? " with status \"$filter\"" : '' ?>.
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
