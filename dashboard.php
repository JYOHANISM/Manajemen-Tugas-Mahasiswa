<?php
session_start();
require 'db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$error = '';
if (isset($_SESSION['edit_error'])) {
    $error = $_SESSION['edit_error'];
    unset($_SESSION['edit_error']);
}

// Ambil username
$query = $conn->prepare("SELECT username FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Ambil semua tugas user
$tugasQuery = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY deadline ASC");
$tugasQuery->bind_param("i", $user_id);
$tugasQuery->execute();
$tugas = $tugasQuery->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard | Tugas Mahasiswa</title>
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
<body>

<!-- Notifikasi Login Berhasil -->
<?php if (isset($_SESSION['login_success'])): ?>
  <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-[#2b2b2b] text-white p-6 rounded-xl w-80 text-center shadow-xl">
      <h2 class="text-lg font-bold text-green-400 mb-3">Login Berhasil</h2>
      <p class="mb-4 text-sm"><?= $_SESSION['login_success']; unset($_SESSION['login_success']); ?></p>
      <button onclick="this.parentElement.parentElement.remove();" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-full">Tutup</button>
    </div>
  </div>
<?php endif; ?>

<div class="container max-w-4xl mx-auto px-4 py-10">

    <!-- Header -->
    <div class="relative bg-zinc-800 text-white rounded-2xl shadow-[6px_6px_0px_#1a1a1a] p-6 text-center mb-10 transition-transform duration-300 transform hover:scale-105 active:scale-95 overflow-hidden">
      <h1 class="text-3xl font-bold">📋 Dashboard Tugas</h1>
      <p class="text-lg mt-2">Selamat Datang, <?= htmlspecialchars($user['username']) ?>!</p>

      <!-- Tombol Settings dan Refresh -->
      <div class="absolute top-4 right-4 flex flex-col items-end gap-2 z-50">
        <button onclick="openModal()" title="Pengaturan / Logout"
          class="bg-neutral-700 hover:bg-neutral-600 text-white p-2 rounded-full shadow-md">
          ⚙️
        </button>
        <button onclick="location.reload()" title="Refresh"
          class="bg-neutral-700 hover:bg-neutral-600 text-white p-2 rounded-full shadow-md">
          🔄
        </button>
      </div>
    </div>

  <!-- Form Tambah Tugas -->
  <form action="add_task.php" method="POST" enctype="multipart/form-data" class="bg-zinc-800 text-white p-6 rounded-2xl shadow-[6px_6px_0px_#1a1a1a] mb-10 hover:scale-105 active:scale-95 transition-transform">
    <h2 class="text-xl font-semibold mb-4">➕ Tambah Tugas Baru</h2>
    <div class="grid gap-4">
      <input type="text" name="title" placeholder="Nama Tugas" required
        class="p-3 border border-gray-500 bg-zinc-700 text-white rounded-lg placeholder-gray-300 focus:ring-2 focus:ring-orange-400">
      <input type="date" name="deadline" required
        class="p-3 border border-gray-500 bg-zinc-700 text-white rounded-lg placeholder-gray-300 focus:ring-2 focus:ring-orange-400">
      <textarea name="description" placeholder="Deskripsi Tugas" rows="3" required
        class="p-3 border border-gray-500 bg-zinc-700 text-white rounded-lg resize-none placeholder-gray-300 focus:ring-2 focus:ring-orange-400"></textarea>
      <input type="file" name="file" accept="*/*"
        class="p-2 border border-gray-500 bg-zinc-700 text-white rounded-lg focus:ring-2 focus:ring-orange-400">
      <button type="submit"
        class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-full mx-auto w-max">
        Tambah Tugas
      </button>
    </div>
  </form>

  <!-- Daftar Tugas -->
  <div class="grid gap-6">
    <?php while ($row = $tugas->fetch_assoc()): ?>
      <div class="bg-zinc-800 text-white p-6 rounded-2xl shadow-[6px_6px_0px_#1a1a1a] hover:scale-105 active:scale-95 transition-transform">
        <div class="flex justify-between items-start mb-3">
          <div>
            <h2 class="text-xl font-semibold"><?= htmlspecialchars($row['title']) ?></h2>
            <span class="text-sm text-gray-300 italic">(<?= $row['status'] ?>)</span>
          </div>
          <div class="text-sm text-orange-300">
            🗓️ <?= date("d M Y", strtotime($row['deadline'])) ?>
          </div>
        </div>
        <p class="text-gray-200 mb-4"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <div class="flex flex-wrap gap-3">
          <?php if (!empty($row['file'])): ?>
            <a href="uploads/<?= htmlspecialchars($row['file']) ?>" download
              class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full flex items-center gap-2">
              📁 Unduh File
            </a>
          <?php endif; ?>
          <?php if ($row['status'] === 'pending'): ?>
            <a href="mark_done.php?id=<?= $row['id'] ?>"
              class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full flex items-center gap-2">
              ✅ Selesai
            </a>
          <?php endif; ?>
          <button onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['title']) ?>', '<?= htmlspecialchars($row['description']) ?>', '<?= htmlspecialchars($row['deadline']) ?>')" 
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-full flex items-center gap-2">
            ✏️ Edit
          </button>
          <a href="delete_task.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full flex items-center gap-2">
            🗑️ Hapus
          </a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

</div>

<!-- Modal Edit Tugas -->
<div id="editTaskModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center hidden">
  <div class="bg-zinc-800 text-white rounded-xl p-6 w-80 text-center shadow-xl">
    <h2 class="text-xl font-bold mb-4 text-yellow-500">Edit Tugas</h2>
    <form id="editTaskForm" action="edit_task.php" method="POST">
      <input type="hidden" name="task_id" id="editTaskId">
      <input type="text" name="title" id="editTitle" required class="w-full p-2 mb-3 bg-zinc-700 text-white rounded-lg" placeholder="Nama Tugas">
      <textarea name="description" id="editDescription" rows="3" required class="w-full p-2 mb-3 bg-zinc-700 text-white rounded-lg" placeholder="Deskripsi Tugas"></textarea>
      <input type="date" name="deadline" id="editDeadline" required class="w-full p-2 mb-3 bg-zinc-700 text-white rounded-lg" placeholder="Deadline">
      <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded-full w-full">Update Tugas</button>
    </form>
    <button onclick="closeEditModal()" class="bg-gray-700 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-full mt-4 w-full">Tutup</button>
  </div>
</div>

<!-- Modal Settings -->
<div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div class="bg-zinc-900 text-white p-6 rounded-xl shadow-lg w-80 text-center">
    <h2 class="text-2xl font-bold text-red-500 mb-6">Logout</h2>
    <p class="mb-6 text-sm">Apakah Anda yakin ingin logout?</p>
    <div class="flex justify-center gap-4">
      <a href="logout.php" class="bg-red-500 px-6 py-2 rounded-md shadow-md hover:bg-pink-600 transition-all">Logout</a>
      <button onclick="closeModal()" class="bg-gray-700 px-6 py-2 rounded-md hover:bg-gray-600 transition-all">Batal</button>
    </div>
  </div>
</div>

<script>
  function openEditModal(id, title, description, deadline) {
    document.getElementById('editTaskId').value = id;
    document.getElementById('editTitle').value = title;
    document.getElementById('editDescription').value = description;
    document.getElementById('editDeadline').value = deadline;
    document.getElementById('editTaskModal').classList.remove('hidden');
  }

  function closeEditModal() {
    document.getElementById('editTaskModal').classList.add('hidden');
  }

  function openModal() {
    document.getElementById('modal').classList.remove('hidden');
  }

  function closeModal() {
    document.getElementById('modal').classList.add('hidden');
  }
</script>

</body>
</html>
