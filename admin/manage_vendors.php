<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";

// Handle verify/unverify
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_verified'])) {
    $vendor_id   = (int)$_POST['vendor_id'];
    $new_verified = (int)$_POST['new_verified'];
    $stmt = mysqli_prepare($conn, "UPDATE vendors SET verified = ? WHERE vendor_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $new_verified, $vendor_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['success_msg'] = "Vendor #$vendor_id verification updated.";
    header("Location: manage_vendors.php");
    exit;
}

$vendors = mysqli_query($conn, "
    SELECT v.vendor_id, v.vendor_name, u.email, u.name AS owner_name,
           v.verified, v.reliability_score, v.created_at
    FROM vendors v
    JOIN users u ON v.user_id = u.user_id
    ORDER BY v.vendor_id ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vendors — KitchenCart Admin</title>
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
            <a href="manage_vendors.php" class="active"><i class="ph ph-storefront"></i> Vendors</a>
            <a href="manage_orders.php"><i class="ph ph-shopping-bag"></i> Orders</a>
            <a href="../auth/logout.php" class="logout-link"><i class="ph ph-sign-out"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Manage Vendors</h1>
            <p class="page-subtitle">View and verify vendors operating on the platform.</p>
        </div>

        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></div>
        <?php endif; ?>

        <div class="card-elevated">
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Vendor Name</th>
                        <th>Owner</th>
                        <th>Email</th>
                        <th>Reliability Score</th>
                        <th>Verified</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($v = mysqli_fetch_assoc($vendors)):
                        $verifiedBadge  = $v['verified']
                            ? "<span class='badge status-accepted'><i class='ph ph-seal-check'></i> Verified</span>"
                            : "<span class='badge status-pending'>Unverified</span>";
                        $toggleLabel    = $v['verified'] ? 'Unverify' : 'Verify';
                        $newVerified    = $v['verified'] ? 0 : 1;
                        $score          = $v['reliability_score'] ?? 'N/A';
                    ?>
                    <tr>
                        <td>#<?= $v['vendor_id'] ?></td>
                        <td><strong><?= htmlspecialchars($v['vendor_name']) ?></strong></td>
                        <td><?= htmlspecialchars($v['owner_name']) ?></td>
                        <td><?= htmlspecialchars($v['email']) ?></td>
                        <td><?= $score ?></td>
                        <td><?= $verifiedBadge ?></td>
                        <td>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="vendor_id" value="<?= $v['vendor_id'] ?>">
                                <input type="hidden" name="new_verified" value="<?= $newVerified ?>">
                                <button type="submit" name="toggle_verified" class="btn-primary btn-sm"><?= $toggleLabel ?></button>
                            </form>
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
