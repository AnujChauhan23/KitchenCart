<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'restaurant') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "../config/db.php";

    $restaurant_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $vendor_id = $_POST['vendor_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO orders (restaurant_id, vendor_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiidi", $restaurant_id, $vendor_id, $product_id, $quantity, $price);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_msg'] = "Order placed successfully!";
        } else {
            $_SESSION['error_msg'] = "Failed to place order.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_msg'] = "Database error.";
    }
    
    header("Location: my_orders.php");
    exit;
} else {
    header("Location: view_prices.php");
    exit;
}
?>