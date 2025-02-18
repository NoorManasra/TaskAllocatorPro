<?php
session_start();
include 'db.php.inc';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

try {
    $query = "
    SELECT 
        t.task_id,
        t.task_name,
        t.description,
        t.start_date AS task_start_date,
        t.end_date AS task_end_date,
        t.priority,
        t.status,
        p.project_title,
        ta.role AS team_role,
        ta.contribution_percentage AS task_contribution
    FROM tasks t
    JOIN project p ON t.project_id = p.project_id
    JOIN task_team_assignments ta ON t.task_id = ta.task_id
    WHERE ta.user_id = :user_id AND ta.status = 'Completed'
    ORDER BY p.project_title, t.task_name";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tasks)) {
        $error = "No completed tasks found.";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Tasks</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>

    <header>
        <div class="header-title">Task Allocator Pro</div>
        <div class="user-profile">
            <a href="profile.php" class="profile-link">My Profile</a>
        </div>
    </header>
<main>
    <div class="nav-bar">
        <ul>
            <li><a href="view_task_member.php" class="nav-link">View My Tasks</a></li>
            <li><a href="update-progress.php" class="nav-link">Update Task Progress</a></li>
             <li><a href="search-task.php" class="nav-link">Search Task</a></li>
        </ul>
    </div>

    <div class="main-section">
        <h2>Completed Tasks</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Task Name</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Contribution (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['project_title']); ?></td>
                        <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['task_start_date']); ?></td>
                        <td><?php echo htmlspecialchars($task['task_end_date']); ?></td>
                        <td><?php echo htmlspecialchars($task['priority']); ?></td>
                        <td><?php echo htmlspecialchars($task['status']); ?></td>
                        <td><?php echo htmlspecialchars($task['team_role']); ?></td>
                        <td><?php echo htmlspecialchars($task['task_contribution']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
 <?php include 'footer.php'; ?>
</body>
</html>
