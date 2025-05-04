<?php session_start(); ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register - Dashboard Tugas</title>
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
    <h1 class="text-2xl font-semibold mb-4 text-center flex items-center justify-center gap-2">📝 <span>Daftar Akun</span></h1>
    <p class="text-center mb-6 text-sm text-gray-300">Isi data kamu buat akses dashboard tugas!</p>

    <form action="register_process.php" method="POST" class="space-y-4">
      <input type="text" name="username" placeholder="Username" required
        class="w-full px-4 py-2 rounded-md bg-zinc-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500">
      <input type="password" name="password" placeholder="Password" required
        class="w-full px-4 py-2 rounded-md bg-zinc-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500">
      <button type="submit"
        class="w-full bg-red-600 hover:bg-red-700 transition text-white font-semibold py-2 rounded-md">
        Daftar
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-400">
      Sudah punya akun?
      <a href="login.php" class="text-red-400 hover:underline font-semibold">Login di sini</a>
    </p>
  </div>

  <!-- Modal Alert -->
  <?php if (isset($_SESSION['register_error'])): ?>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-zinc-800 text-white p-6 rounded-xl w-80 text-center shadow-xl">
      <h2 class="text-lg font-bold text-red-500 mb-3">Gagal Registrasi</h2>
      <p class="mb-4 text-sm"><?= $_SESSION['register_error']; unset($_SESSION['register_error']); ?></p>
      <button onclick="this.parentElement.parentElement.remove();"
        class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-full">
        Tutup
      </button>
    </div>
  </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['register_success'])): ?>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-zinc-800 text-white p-6 rounded-xl w-80 text-center shadow-xl">
      <h2 class="text-lg font-bold text-green-400 mb-3">Registrasi Berhasil!</h2>
      <p class="mb-4 text-sm"><?= $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
      <a href="login.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full inline-block">Login Sekarang</a>
    </div>
  </div>
  <?php endif; ?>

</body>
</html>
