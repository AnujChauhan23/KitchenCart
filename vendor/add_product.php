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

<h3>Add Product</h3>

<form method="post">
    Product Name:
    <input type="text" name="product_name" required><br><br>

    Category:
    <input type="text" name="category" required><br><br>

    <button type="submit">Add Product</button>
</form>

<?php
if ($_POST) {
    $name = $_POST['product_name'];
    $cat  = $_POST['category'];

    mysqli_query($conn,
        "INSERT INTO products (vendor_id, product_name, category)
         VALUES ($vendor_id, '$name', '$cat')"
    );

    echo "✅ Product added successfully";
}
?>