<?php
session_start();
include 'db.php.inc';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    try {
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':user_id' => $delete_id]);

        $_SESSION['success_message'] = "User deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error deleting user: " . $e->getMessage();
    }
} else {
    $_SESSION['error_message'] = "No user ID provided for deletion.";
}

header("Location: manage_users.php");
exit;
