<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

// Handle toggle active/inactive
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    $user_id    = (int)$_POST['user_id'];
    $new_status = (int)$_POST['new_status'];
    $stmt = mysqli_prepare($conn, "UPDATE users SET status = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $new_status, $user_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['success_msg'] = "User #$user_id status updated.";
    header("Location: manage_users.php");
    exit;
}

$users = mysqli_query($conn, "SELECT user_id, name, email, role, status, created_at FROM users ORDER BY user_id ASC");

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users — KitchenCart Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <a href="dashboard.php" class="sidebar-logo" style="text-decoration:none;"><i class="ph-fill ph-storefront"></i> KitchenCart</a>
        <nav class="nav-links">
            <a href="dashboard.php"><i class="ph ph-squares-four"></i> Dashboard</a>
            <a href="manage_users.php" class="active"><i class="ph ph-users"></i> Users</a>
            <a href="manage_vendors.php"><i class="ph ph-storefront"></i> Vendors</a>
            <a href="manage_orders.php"><i class="ph ph-shopping-bag"></i> Orders</a>
            <a href="../auth/logout.php" class="logout-link"><i class="ph ph-sign-out"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Users</h1>
            <p class="page-subtitle">View and manage all registered users on the platform.</p>
        </div>

        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>

        <div class="card-elevated">
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $roleColors = [
                        'admin'      => 'background-color:hsl(220 50% 92%);color:hsl(220 50% 30%);',
                        'vendor'     => 'background-color:hsl(var(--primary)/0.1);color:hsl(var(--primary));',
                        'restaurant' => 'background-color:hsl(35 85% 55%/0.15);color:hsl(35 85% 35%);',
                    ];
                    while ($u = mysqli_fetch_assoc($users)):
                        $roleStyle   = $roleColors[$u['role']] ?? '';
                        $statusLabel = $u['status'] ? "<span class='badge status-accepted'>Active</span>" : "<span class='badge status-pending'>Inactive</span>";
                        $toggleLabel = $u['status'] ? 'Deactivate' : 'Activate';
                        $newStatus   = $u['status'] ? 0 : 1;
                    ?>
                    <tr>
                        <td>#<?= $u['user_id'] ?></td>
                        <td><?= htmlspecialchars($u['name']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge" style="<?= $roleStyle ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td><?= date('d M Y', strtotime($u['created_at'] ?? 'now')) ?></td>
                        <td><?= $statusLabel ?></td>
                        <td>
                            <?php if ($u['role'] !== 'admin'): ?>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                                <input type="hidden" name="new_status" value="<?= $newStatus ?>">
                                <button type="submit" name="toggle_status" class="btn-primary btn-sm"><?= $toggleLabel ?></button>
                            </form>
                            <?php else: ?>
                                <span style="color:hsl(var(--muted-foreground));font-size:0.8rem;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </main>
</div>

</body>
</html>
