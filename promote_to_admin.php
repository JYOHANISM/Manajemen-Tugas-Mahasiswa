<?php
session_start();
include 'db/config.php';

// Cek apakah pengguna yang login adalah admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil ID pengguna yang akan dipromosikan menjadi admin
$user_id = $_GET['user_id'];

// Update role menjadi admin
$query = "UPDATE users SET role = 'admin' WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

// Redirect ke halaman manage users
header("Location: manage_users.php");
exit;
?>
