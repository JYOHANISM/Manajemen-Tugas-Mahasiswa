<?php
session_start();
$_SESSION['logout_success'] = "Logout berhasil.";
session_destroy();
header("Location: login.php");
exit();
