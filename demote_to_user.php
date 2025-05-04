<?php
session_start();
include 'db/config.php';

// Pastikan admin yang sedang login
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter user_id
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query untuk update role pengguna
    $query = "UPDATE users SET role = 'user' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Role pengguna berhasil diubah menjadi User.";
    } else {
        $_SESSION['error'] = "Gagal mengubah role pengguna.";
    }

    header("Location: manage_users.php");
    exit;
} else {
    $_SESSION['error'] = "User ID tidak ditemukan.";
    header("Location: manage_users.php");
    exit;
}
?>
