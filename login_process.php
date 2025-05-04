<?php
session_start();
include 'db/config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (empty($username) || empty($password)) {
    $_SESSION['login_error'] = "Username dan password wajib diisi.";
    header("Location: login.php");
    exit;
}

if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role']; // Menyimpan role (admin/user)
        $_SESSION['login_success'] = "Login berhasil! Selamat datang kembali, $username.";
        
        // Jika admin, arahkan ke admin dashboard
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $_SESSION['login_error'] = "Password salah.";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "Username tidak ditemukan.";
    header("Location: login.php");
    exit;
}
?>
