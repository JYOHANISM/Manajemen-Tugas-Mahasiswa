<?php
session_start();
require 'db/config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    if ($_FILES['file']['name']) {
        $file_name = basename($_FILES['file']['name']);
        $file_path = "uploads/" . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    } else {
        $file_name = null;
    }

    // Update data tugas
    $update = $conn->prepare("UPDATE tasks SET title=?, deadline=?, description=?, status=?, file=? WHERE id=? AND user_id=?");
    $update->bind_param("ssssssi", $title, $deadline, $description, $status, $file_name, $task_id, $user_id);
    $update->execute();

    echo json_encode(['success' => true]);
}
