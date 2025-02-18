<?php
include('db.php.inc');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$task_id = $_GET['task_id'];

try {
    $query = "UPDATE tasks SET status = 'Accepted' WHERE task_id = :task_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['task_id' => $task_id]);

    header("Location: view_task_member.php");
} catch (PDOException $e) {
    die("Error updating task: " . $e->getMessage());
}
?>
