<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<h2>Place Order</h2>

<form method="post">
    Product ID: <input type="number" name="product_id" required><br><br>
    Vendor ID: <input type="number" name="vendor_id" required><br><br>
    Quantity: <input type="number" name="qty" required><br><br>
    Price per unit: <input type="number" step="0.01" name="price" required><br><br>
    <button type="submit">Place Order</button>
</form>

<?php
if ($_POST) {
    $restaurant_id = 1; // test restaurant
    $vendor_id = $_POST['vendor_id'];
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];
    $price = $_POST['price'];
    $today = date('Y-m-d');

    mysqli_query($conn,
        "INSERT INTO orders (restaurant_id, vendor_id, order_date)
         VALUES ($restaurant_id, $vendor_id, '$today')"
    );

    $order_id = mysqli_insert_id($conn);

    mysqli_query($conn,
        "INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES ($order_id, $product_id, $qty, $price)"
    );

    echo "Order placed successfully!";
}
?>