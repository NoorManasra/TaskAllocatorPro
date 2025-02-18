<?php
session_start();
include 'db.php.inc';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // استرجاع user_id من الجلسة

// التحقق من وجود task_id في الرابط
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    try {
        // تحقق من أن المستخدم هو الذي أنشأ المهمة (لأغراض الأمان)
        $checkQuery = "
            SELECT * FROM tasks
            WHERE task_id = :task_id
            AND project_id IN (
                SELECT project_id FROM project_team_leaders WHERE user_id = :user_id
            )
        ";

        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([':task_id' => $task_id, ':user_id' => $user_id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($task) {
            // حذف المهمة والمهام المرتبطة بها
            $deleteQuery = "
                DELETE t, ta 
                FROM tasks t
                LEFT JOIN task_team_assignments ta ON t.task_id = ta.task_id
                WHERE t.task_id = :task_id
            ";

            $deleteStmt = $pdo->prepare($deleteQuery);
            $deleteStmt->execute([':task_id' => $task_id]);

            // إعادة التوجيه بعد الحذف
            header("Location: view_task.php?success=Task deleted successfully");
        } else {
            header("Location: view_task.php?error=Unauthorized access or task not found");
        }
    } catch (PDOException $e) {
        header("Location: view_task.php?error=" . $e->getMessage());
    }
} else {
    header("Location: view_task.php?error=Task ID is missing");
}
exit();
?>
