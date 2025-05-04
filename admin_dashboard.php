<?php session_start(); ?>

<?php
// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Sistem Manajemen Tugas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: linear-gradient(to bottom, #ff1e56, #1e1e1e);
      min-height: 100vh;
    }
  </style>
</head>
<body class="flex items-center justify-center">

  <div class="relative bg-zinc-800 text-white p-8 rounded-2xl shadow-[6px_6px_0px_#1a1a1a] w-full max-w-md transition-transform duration-300 transform hover:scale-105 active:scale-95 overflow-hidden">
    <h1 class="text-2xl font-semibold mb-4 text-center flex items-center justify-center gap-2">📋 <span>Admin Dashboard</span></h1>
    <p class="text-center mb-6 text-sm text-gray-300">Selamat datang, Admin! Kamu dapat mengelola pengguna di sini.</p>

    <div class="space-y-4">
      <!-- Kelola Pengguna -->
      <div class="flex justify-center">
        <a href="manage_users.php" class="w-full bg-red-600 hover:bg-red-700 transition text-white font-semibold py-2 rounded-md text-center">Kelola Pengguna</a>
      </div>

      <!-- Logout -->
      <div class="flex justify-center">
        <a href="logout.php" class="w-full bg-gray-700 hover:bg-gray-600 transition text-white font-semibold py-2 rounded-md text-center">Logout</a>
      </div>
    </div>

  </div>

  <!-- Modal Alert -->
  <?php if (isset($_SESSION['login_error'])): ?>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-zinc-800 text-white p-6 rounded-xl w-80 text-center shadow-xl">
      <h2 class="text-lg font-bold text-red-500 mb-3">Login Gagal</h2>
      <p class="mb-4 text-sm"><?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
      <button onclick="this.parentElement.parentElement.remove();"
        class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-full">
        Tutup
      </button>
    </div>
  </div>
  <?php endif; ?>

</body>
</html>
