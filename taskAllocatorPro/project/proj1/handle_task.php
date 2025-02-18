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
            $success_message = "Task Accepted.";
        } elseif ($action === 'reject') {
            $query = "UPDATE task_team_assignments SET status = 'Rejected' WHERE task_id = :task_id AND user_id = :user_id";
            $success_message = "Task Rejected.";
        } else {
            throw new Exception("Invalid action.");
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute([':task_id' => $task_id, ':user_id' => $_SESSION['user_id']]);

        header("Location: view_task_member.php?success=" . urlencode($success_message));
        exit();
    } catch (PDOException $e) {
        header("Location: view_task_member.php?error=Database error: " . urlencode($e->getMessage()));
        exit();
    } catch (Exception $e) {
        header("Location: view_task_member.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: view_task_member.php?error=Invalid request.");
    exit();
}
