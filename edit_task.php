<?php
session_start();
require 'db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user_id'];

    // Validasi dasar
    if (empty($task_id) || empty($title) || empty($description) || empty($deadline)) {
        $_SESSION['edit_error'] = "Semua field harus diisi!";
        header("Location: dashboard.php");
        exit();
    }

    // Update data
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, deadline = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sssii", $title, $description, $deadline, $task_id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['edit_error'] = "Gagal mengupdate tugas.";
        header("Location: dashboard.php");
        exit();
    }
}
?>
