<?php
session_start();
// Redirect if already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') header("Location: ../admin/dashboard.php");
    elseif ($_SESSION['role'] == 'vendor') header("Location: ../vendor/dashboard.php");
    else header("Location: ../restaurant/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started — KitchenCart</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .auth-card { max-width: 460px; }

        .role-picker {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1.25rem 1rem;
            border: 2px solid hsl(var(--border));
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s;
            background-color: transparent;
            text-align: center;
        }

        .role-card i {
            font-size: 1.75rem;
            color: hsl(var(--muted-foreground));
            transition: color 0.2s;
        }

        .role-card span {
            font-size: 0.875rem;
            font-weight: 600;
            color: hsl(var(--foreground));
        }

        .role-card small {
            font-size: 0.75rem;
            color: hsl(var(--muted-foreground));
            line-height: 1.3;
        }

        .role-option input:checked + .role-card {
            border-color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.07);
        }

        .role-option input:checked + .role-card i {
            color: hsl(var(--primary));
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: hsl(var(--muted-foreground));
        }

        .auth-footer a {
            color: hsl(var(--primary));
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-title">
            <div class="logo"><i class="ph-fill ph-storefront" style="color: hsl(var(--primary));"></i></div>
            Create your account
        </div>

        <?php
        if (isset($_SESSION['error_msg'])) {
            echo "<div class='alert alert-error'>" . $_SESSION['error_msg'] . "</div>";
            unset($_SESSION['error_msg']);
        }
        ?>

        <form action="register_process.php" method="POST">

            <!-- Role Picker -->
            <p class="form-label" style="margin-bottom:0.75rem;">I am a…</p>
            <div class="role-picker">
                <label class="role-option">
                    <input type="radio" name="role" value="restaurant" required>
                    <div class="role-card">
                        <i class="ph ph-fork-knife"></i>
                        <span>Restaurant</span>
                        <small>Buy ingredients from vendors</small>
                    </div>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="vendor">
                    <div class="role-card">
                        <i class="ph ph-storefront"></i>
                        <span>Vendor</span>
                        <small>Sell raw materials to restaurants</small>
                    </div>
                </label>
            </div>

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="input-base" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="input-base" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password-input" class="input-base" placeholder="Create a password" style="padding-right: 2.75rem;" required>
                    <button type="button" onclick="
                        var inp = document.getElementById('password-input');
                        var icon = document.getElementById('eye-icon');
                        if (inp.type === 'password') {
                            inp.type = 'text';
                            icon.className = 'ph ph-eye-slash';
                        } else {
                            inp.type = 'password';
                            icon.className = 'ph ph-eye';
                        }
                    " style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:hsl(var(--muted-foreground));font-size:1.1rem;display:flex;align-items:center;padding:0;">
                        <i id="eye-icon" class="ph ph-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Vendor-only: Vendor Name -->
            <div class="form-group" id="vendor-name-group" style="display:none;">
                <label class="form-label">Business / Vendor Name</label>
                <input type="text" name="vendor_name" id="vendor-name-input" class="input-base" placeholder="Enter your business name">
            </div>

            <button type="submit" class="btn-primary auth-btn" style="margin-top:0.5rem;">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Sign In</a>
        </div>
    </div>
</div>

<script>
    // Show/hide vendor name field based on role selection
    document.querySelectorAll('input[name="role"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var vendorGroup = document.getElementById('vendor-name-group');
            var vendorInput = document.getElementById('vendor-name-input');
            if (this.value === 'vendor') {
                vendorGroup.style.display = 'block';
                vendorInput.setAttribute('required', 'required');
            } else {
                vendorGroup.style.display = 'none';
                vendorInput.removeAttribute('required');
            }
        });
    });
</script>

</body>
</html>
