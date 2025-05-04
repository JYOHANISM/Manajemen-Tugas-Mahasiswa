<?php
session_start();
require 'db/config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE tasks SET status = 'done' WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();

header("Location: dashboard.php");
?>

register_process.php
<?php
require 'db/config.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    header("Location: index.php?register=success");
} else {
    echo "Gagal daftar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
