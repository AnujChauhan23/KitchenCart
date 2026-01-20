<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}

$query = "
SELECT 
    p.product_name,
    v.vendor_name,
    dp.price AS today_price,
    (
        SELECT price 
        FROM daily_prices dp2 
        WHERE dp2.product_id = dp.product_id 
          AND dp2.vendor_id = dp.vendor_id 
          AND dp2.price_date < dp.price_date
        ORDER BY dp2.price_date DESC
        LIMIT 1
    ) AS yesterday_price,
    dp.price_date
FROM daily_prices dp
JOIN products p ON dp.product_id = p.product_id
JOIN vendors v ON dp.vendor_id = v.vendor_id
ORDER BY p.product_name, dp.price ASC
";

$result = mysqli_query($conn, $query);
?>

<h2>Price Comparison</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Product</th>
    <th>Vendor</th>
    <th>Price</th>
    <th>Date</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['vendor_name'] ?></td>
    <td>
₹<?= $row['today_price'] ?>

<?php
if ($row['yesterday_price']) {
    if ($row['today_price'] > $row['yesterday_price']) {
        echo " 🔺";
    } elseif ($row['today_price'] < $row['yesterday_price']) {
        echo " 🔻";
    } else {
        echo " ⏺";
    }
}
?>
</td>
    <td><?= $row['price_date'] ?></td>
</tr>
<?php } ?>
</table>