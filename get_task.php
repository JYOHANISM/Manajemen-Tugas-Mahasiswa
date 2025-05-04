<?php
session_start();
require 'db/config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

// Ambil data tugas
$query = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$query->bind_param("ii", $task_id, $user_id);
$query->execute();
$result = $query->get_result();
$task = $result->fetch_assoc();

if ($task) {
    echo json_encode(['success' => true, 'task' => $task]);
} else {
    echo json_encode(['success' => false]);
}
