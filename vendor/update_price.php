<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'vendor') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$getVendor = mysqli_query($conn,
    "SELECT vendor_id FROM vendors WHERE user_id=$user_id"
);
$vendor = mysqli_fetch_assoc($getVendor);
$vendor_id = $vendor['vendor_id'];
?>

<h3>Update Daily Price</h3>

<form method="post">
    Product ID:
    <input type="number" name="product_id" required><br><br>

    Price:
    <input type="number" step="0.01" name="price" required><br><br>

    <button type="submit">Update Price</button>
</form>

<?php
if ($_POST) {
    $product_id = $_POST['product_id'];
    $price = $_POST['price'];
    $today = date('Y-m-d');

    mysqli_query($conn,
        "INSERT INTO daily_prices (vendor_id, product_id, price, price_date)
         VALUES ($vendor_id, $product_id, $price, '$today')"
    );

    echo "✅ Price updated for today";
}
?>