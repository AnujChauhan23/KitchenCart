<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ../admin/dashboard.php");
    } elseif ($_SESSION['role'] == 'vendor') {
        header("Location: ../vendor/dashboard.php");
    } else {
        header("Location: ../restaurant/dashboard.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KitchenCart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-title">
            <div class="logo"><i class="ph-fill ph-storefront" style="color: hsl(var(--primary));"></i></div>
            KitchenCart
        </div>
        
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo "<div class='alert alert-error' style='text-align: center;'>" . $_SESSION['error_msg'] . "</div>";
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['success_msg'])) {
            echo "<div class='alert alert-success' style='text-align: center;'>" . $_SESSION['success_msg'] . "</div>";
            unset($_SESSION['success_msg']);
        }
        ?>

        <form action="login_process.php" method="post">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="input-base" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password-input" class="input-base" placeholder="Enter your password" style="padding-right: 2.75rem;" required>
                    <button type="button" id="toggle-password" onclick="
                        var inp = document.getElementById('password-input');
                        var icon = document.getElementById('eye-icon');
                        if (inp.type === 'password') {
                            inp.type = 'text';
                            icon.className = 'ph ph-eye-slash';
                        } else {
                            inp.type = 'password';
                            icon.className = 'ph ph-eye';
                        }
                    " style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: hsl(var(--muted-foreground)); font-size: 1.1rem; display: flex; align-items: center; padding: 0;">
                        <i id="eye-icon" class="ph ph-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary auth-btn">Sign In</button>
        </form>
    </div>
</div>

</body>
</html>