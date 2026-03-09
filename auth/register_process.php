<?php
session_start();
include "../config/db.php";

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

// Basic validation
if (!$name || !$email || !$password || !$role) {
    $_SESSION['error_msg'] = "All fields are required.";
    header("Location: register.php");
    exit;
}

if (!in_array($role, ['restaurant', 'vendor'])) {
    $_SESSION['error_msg'] = "Invalid role selected.";
    header("Location: register.php");
    exit;
}

// Check for duplicate email
$check = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    $_SESSION['error_msg'] = "An account with this email already exists.";
    header("Location: register.php");
    exit;
}

// Insert user (status = 1 = active)
$stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, 1)");
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $role);

if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['error_msg'] = "Registration failed. Please try again.";
    header("Location: register.php");
    exit;
}

$new_user_id = mysqli_insert_id($conn);

// If vendor, also create a vendors record
if ($role === 'vendor') {
    $vendor_name = trim($_POST['vendor_name'] ?? $name);
    $vstmt = mysqli_prepare($conn, "INSERT INTO vendors (user_id, vendor_name, verified, reliability_score) VALUES (?, ?, 0, 0)");
    mysqli_stmt_bind_param($vstmt, "is", $new_user_id, $vendor_name);
    mysqli_stmt_execute($vstmt);
}

// Auto-login after registration
$_SESSION['user_id'] = $new_user_id;
$_SESSION['role']    = $role;

$_SESSION['success_msg'] = "Welcome to KitchenCart, $name! Your account was created successfully.";

if ($role === 'vendor') {
    header("Location: ../vendor/dashboard.php");
} else {
    header("Location: ../restaurant/dashboard.php");
}
exit;
