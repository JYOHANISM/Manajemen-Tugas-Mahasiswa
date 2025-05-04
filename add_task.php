<?php
session_start();
require 'db/config.php';
// setelah tugas berhasil disimpan

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $deadline = $_POST['deadline'];
  $status = 'pending';

  // Proses file jika ada
  $filename = '';
  if (!empty($_FILES['file']['name'])) {
    $filename = uniqid() . '_' . basename($_FILES['file']['name']);
    $target_path = 'uploads/' . $filename;
    move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
  }

  $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, deadline, status, file) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("isssss", $user_id, $title, $description, $deadline, $status, $filename);
  $stmt->execute();

  header("Location: dashboard.php");
  exit;
}
?>
