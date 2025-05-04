<?php
session_start();
include 'db/config.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['register_error'] = "Username dan password wajib diisi.";
    header("Location: register.php");
    exit();
}

$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $_SESSION['register_error'] = "Username sudah dipakai.";
    header("Location: register.php");
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$insert->bind_param("ss", $username, $hashed_password);

if ($insert->execute()) {
    $_SESSION['register_success'] = "Registrasi berhasil! Silakan login.";
    header("Location: register.php");
} else {
    $_SESSION['register_error'] = "Terjadi kesalahan saat menyimpan data.";
    header("Location: register.php");
}
?>
