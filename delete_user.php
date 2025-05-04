<?php
session_start();
include 'db/config.php';

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ID pengguna yang akan dihapus ada
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Query untuk menghapus pengguna berdasarkan ID
    $query = "DELETE FROM users WHERE id = ?";
    
    // Persiapkan statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        
        // Jalankan query
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = 'Pengguna berhasil dihapus!';
            header("Location: manage_users.php");
            exit;
        } else {
            $_SESSION['error_message'] = 'Terjadi kesalahan saat menghapus pengguna!';
        }
    } else {
        $_SESSION['error_message'] = 'Gagal menyiapkan query untuk penghapusan!';
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}

// Jika tidak ada ID yang diberikan, kembalikan ke halaman manage_users.php
header("Location: manage_users.php");
exit;
?>
