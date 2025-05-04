<?php
session_start();
include 'db/config.php';

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua data pengguna dari database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manage Users - Dashboard Tugas</title>
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

  <div class="relative bg-zinc-800 text-white p-8 rounded-2xl shadow-[6px_6px_0px_#1a1a1a] w-full max-w-4xl transition-transform duration-300 transform hover:scale-105 active:scale-95 overflow-hidden">
    <h1 class="text-2xl font-semibold mb-4 text-center flex items-center justify-center gap-2">👥 Kelola Pengguna</h1>
    <p class="text-center mb-6 text-sm text-gray-300">Manajemen pengguna yang ada di sistem</p>

    <!-- Tampilkan pesan sukses atau error -->
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="bg-green-500 text-white p-4 mb-4 rounded-md">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
    </div>
    <?php elseif (isset($_SESSION['error_message'])): ?>
    <div class="bg-red-500 text-white p-4 mb-4 rounded-md">
        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
    </div>
    <?php endif; ?>

    <!-- Tabel Pengguna -->
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-left text-gray-300">
        <thead class="bg-zinc-700">
          <tr>
            <th class="px-4 py-2">No</th>
            <th class="px-4 py-2">Username</th>
            <th class="px-4 py-2">Role</th>
            <th class="px-4 py-2 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Menampilkan data pengguna dalam tabel
          $no = 1;
          while ($user = mysqli_fetch_assoc($result)) {
          ?>
          <tr class="bg-zinc-800 hover:bg-zinc-700 transition duration-200">
            <td class="px-4 py-2"><?php echo $no++; ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
            <td class="px-4 py-2"><?php echo ucfirst($user['role']); ?></td>
            <td class="px-4 py-2 text-center">
              <div class="flex justify-center gap-4">
                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                  Edit
                </a>
                <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm">
                  Hapus
                </a>
              </div>
            </td>
          </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6 text-center">
      <a href="admin_dashboard.php" class="text-sm text-red-400 hover:underline">Kembali ke Dashboard</a>
    </div>
  </div>

</body>
</html>
