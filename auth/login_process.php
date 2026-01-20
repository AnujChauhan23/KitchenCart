<?php
session_start();
include "../config/db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email='$email' AND password='$password' AND status=1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } elseif ($user['role'] == 'vendor') {
        header("Location: ../vendor/dashboard.php");
    } else {
        header("Location: ../restaurant/dashboard.php");
    }
} else {
    echo "Invalid login credentials";
}
?>
