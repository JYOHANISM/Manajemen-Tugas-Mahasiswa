<?php
session_start();
include 'db/config.php';

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter ID pengguna yang diberikan
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Ambil data pengguna dari database berdasarkan ID
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // Cek apakah pengguna ditemukan
    if (!$user) {
        echo "Pengguna tidak ditemukan.";
        exit;
    }
} else {
    echo "ID pengguna tidak diberikan.";
    exit;
}

// Proses update data pengguna setelah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Validasi data
    if (empty($username) || empty($role)) {
        echo "Semua field harus diisi!";
        exit;
    }

    // Update data pengguna
    $update_query = "UPDATE users SET username = ?, role = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssi", $username, $role, $user_id);
    $update_result = mysqli_stmt_execute($update_stmt);

    // Menyimpan pesan sukses di session untuk ditampilkan
    if ($update_result) {
        $_SESSION['update_message'] = "Pengguna berhasil diperbarui!";
        header("Location: edit_user.php?id=$user_id"); // Refresh halaman untuk menampilkan pop-up
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui data pengguna.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengguna</title>
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
    <h1 class="text-2xl font-semibold mb-4 text-center flex items-center justify-center gap-2">✏️ Edit Pengguna</h1>

    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST" class="space-y-6">
      <div>
        <label for="username" class="block text-sm font-medium text-gray-300">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($user['username']); ?>"
          class="w-full px-4 py-2 rounded-md bg-zinc-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500">
      </div>

      <div>
        <label for="role" class="block text-sm font-medium text-gray-300">Role</label>
        <select id="role" name="role" required
          class="w-full px-4 py-2 rounded-md bg-zinc-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500">
          <option value="admin" <?php echo ($user['role'] == 'admin' ? 'selected' : ''); ?>>Admin</option>
          <option value="user" <?php echo ($user['role'] == 'user' ? 'selected' : ''); ?>>User</option>
        </select>
      </div>

      <div>
        <button type="submit"
          class="w-full bg-red-600 hover:bg-red-700 transition text-white font-semibold py-2 rounded-md">
          Update Pengguna
        </button>
      </div>
    </form>

    <div class="mt-6 text-center">
      <a href="admin_dashboard.php" class="text-sm text-red-400 hover:underline">Kembali ke Dashboard</a>
    </div>
  </div>

  <!-- Modal Alert -->
  <?php if (isset($_SESSION['update_message'])): ?>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-zinc-800 text-white p-6 rounded-xl w-80 text-center shadow-xl">
      <h2 class="text-lg font-bold text-green-500 mb-3">Berhasil!</h2>
      <p class="mb-4 text-sm"><?= $_SESSION['update_message']; unset($_SESSION['update_message']); ?></p>
      <button onclick="this.parentElement.parentElement.remove();"
        class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-full">
        Tutup
      </button>
    </div>
  </div>
  <?php endif; ?>

</body>
</html>
