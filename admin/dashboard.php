<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
echo "Welcome Admin!";
?>
<br><br>
<a href="../auth/logout.php">Logout</a>
</ul>