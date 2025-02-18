<?php
session_start();
include 'db.php.inc';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['action'])) {
    $task_id = $_POST['task_id'];
    $action = $_POST['action'];

    try {
        if ($action === 'accept') {
            $query = "UPDATE task_team_assignments SET status = 'Accepted' WHERE task_id = :task_id AND user_id = :user_id";
        } elseif ($action === 'reject') {
            $query = "UPDATE task_team_assignments SET status = 'Rejected' WHERE task_id = :task_id AND user_id = :user_id";
        } elseif ($action === 'complete') {
            $query = "UPDATE task_team_assignments SET status = 'Completed' WHERE task_id = :task_id AND user_id = :user_id";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute([':task_id' => $task_id, ':user_id' => $_SESSION['user_id']]);

        if ($action === 'complete') {
            header("Location: completed-tasks.php?success=Task marked as completed.");
        } else {
            header("Location: view_task_member.php?success=Task $action successfully.");
        }
        exit();
    } catch (PDOException $e) {
        header("Location: view_task_member.php?error=Database error: " . $e->getMessage());
        exit();
    }
} else {
    header("Location: view_task_member.php?error=Invalid request.");
    exit();
}
