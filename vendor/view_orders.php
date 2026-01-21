<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit;
}

$vendor_id = 1; // test vendor
$result = mysqli_query($conn,
    "SELECT o.order_id, o.order_date, o.status, oi.quantity, oi.price, p.product_name
     FROM orders o
     JOIN order_items oi ON o.order_id = oi.order_id
     JOIN products p ON oi.product_id = p.product_id
     WHERE o.vendor_id = $vendor_id"
);
?>

<h2>Incoming Orders</h2>

<table border="1">
<tr>
    <th>Order ID</th>
    <th>Product</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Status</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['order_id'] ?></td>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td>₹<?= $row['price'] ?></td>
    <td><?= $row['status'] ?></td>
</tr>
<?php } ?>
</table>