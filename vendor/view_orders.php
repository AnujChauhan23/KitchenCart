<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";
include "../includes/header.php";

$vendor_id = $_SESSION['user_id'];

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $update_query = "UPDATE orders SET status = ? WHERE order_id = ? AND vendor_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sii", $new_status, $order_id, $vendor_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_msg'] = "Order #{$order_id} status updated to {$new_status}.";
        } else {
            $_SESSION['error_msg'] = "Failed to update order status.";
        }
        mysqli_stmt_close($stmt);
    }
    
    // Redirect to prevent form resubmission
    header("Location: view_orders.php");
    exit;
}

// Fetch vendor's orders
$query = "
SELECT 
    o.order_id,
    p.product_name,
    u.name AS restaurant_name,
    o.quantity,
    o.price,
    o.order_date,
    o.status
FROM orders o
JOIN products p ON o.product_id = p.product_id
JOIN users u ON o.restaurant_id = u.user_id
WHERE o.vendor_id = ?
ORDER BY o.order_date DESC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $vendor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div class="container">

    <div class="page-header">
        <h1 class="page-title">Incoming Orders</h1>
        <p class="page-subtitle">
            Manage and update orders placed by restaurants.
        </p>
    </div>

    <!-- CARD START -->
    <div class="card-elevated">
        
        <?php
        if (isset($_SESSION['success_msg'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success_msg'] . "</div>";
            unset($_SESSION['success_msg']);
        }
        if (isset($_SESSION['error_msg'])) {
            echo "<div class='alert alert-error'>" . $_SESSION['error_msg'] . "</div>";
            unset($_SESSION['error_msg']);
        }
        ?>

        <div class="table-wrapper">
            <table>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Restaurant</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Date</th>
                <th>Status</th>
                <th>Update Status</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    $statusClass = 'status-' . strtolower($row['status']);
                    $statusLabel = "<span class='badge {$statusClass}'>{$row['status']}</span>";

                    echo "<tr>";
                    echo "<td>#{$row['order_id']}</td>";
                    echo "<td>{$row['product_name']}</td>";
                    echo "<td>{$row['restaurant_name']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td>₹{$row['price']}</td>";
                    echo "<td>" . date('Y-m-d H:i', strtotime($row['order_date'])) . "</td>";
                    echo "<td>{$statusLabel}</td>";
                    echo "<td>
                            <form method='POST' style='margin:0; display:flex; gap:0.5rem; align-items:center;'>
                                <input type='hidden' name='order_id' value='{$row['order_id']}'>
                                <select name='status' class='input-base' style='width: auto; padding-top: 0.25rem; padding-bottom: 0.25rem;'>
                                    <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                    <option value='Accepted' " . ($row['status'] == 'Accepted' ? 'selected' : '') . ">Accepted</option>
                                    <option value='Delivered' " . ($row['status'] == 'Delivered' ? 'selected' : '') . ">Delivered</option>
                                </select>
                                <button type='submit' name='update_status' class='btn-primary btn-sm'>Update</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No incoming orders found.</td></tr>";
            }
            ?>

            </table>
        </div>

    </div>
    <!-- CARD END -->

</div>
</main>
</div>
</body>
</html>