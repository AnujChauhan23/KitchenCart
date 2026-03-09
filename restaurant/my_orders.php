<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}

include "../config/db.php";
include "../includes/header.php";

$restaurant_id = $_SESSION['user_id'];

$query = "
SELECT 
    o.order_id,
    p.product_name,
    v.vendor_name,
    o.quantity,
    o.price,
    (o.quantity * o.price) as total_price,
    o.order_date,
    o.status
FROM orders o
JOIN products p ON o.product_id = p.product_id
JOIN vendors v ON o.vendor_id = v.vendor_id
WHERE o.restaurant_id = ?
ORDER BY o.order_date DESC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<div class="container">

    <div class="page-header">
        <h1 class="page-title">My Orders</h1>
        <p class="page-subtitle">
            History of all orders placed by your restaurant.
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
                <th>Vendor</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    
                    $statusClass = 'status-' . strtolower($row['status']);
                    $statusLabel = "<span class='badge {$statusClass}'>{$row['status']}</span>";

                    echo "<tr>";
                    echo "<td>#{$row['order_id']}</td>";
                    echo "<td>{$row['product_name']}</td>";
                    echo "<td>{$row['vendor_name']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td>₹{$row['price']}</td>";
                    echo "<td>₹{$row['total_price']}</td>";
                    echo "<td>" . date('Y-m-d H:i', strtotime($row['order_date'])) . "</td>";
                    echo "<td>{$statusLabel}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No orders found.</td></tr>";
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
