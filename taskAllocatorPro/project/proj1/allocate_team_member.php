<?php
session_start();
include 'db.php.inc';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

$query = "SELECT t.task_id, t.task_name, t.start_date, t.status, t.priority, p.project_title 
          FROM tasks t
          JOIN project p ON t.project_id = p.project_id
          JOIN project_team_leaders ptl ON ptl.project_id = p.project_id
          WHERE ptl.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Team Members to Tasks</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>

    <header>
        <div class="header-title">Task Allocator Pro</div>
        <div class="user-profile">
            <a href="profile.php" class="profile-link">My Profile</a>
        </div>
    </header>

    <div class="nav-bar">
        <ul>
            <li><a href="view_task.php" class="nav-link">View Tasks</a></li>
            <li><a href="allocate_team_member.php" class="nav-link active">Assign Team Members</a></li>
        </ul>
    </div>

    <div class="main-section">
        <h2>Assign Team Members to Tasks</h2>
        
        <?php if (empty($tasks)) : ?>
            <p class="error">No tasks available for assignment.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Task Name</th>
                        <th>Project</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($task['task_id']); ?></td>
                            <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                            <td><?php echo htmlspecialchars($task['project_title']); ?></td>
                            <td><?php echo htmlspecialchars($task['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($task['status']); ?></td>
                            <td><?php echo htmlspecialchars($task['priority']); ?></td>
                            <td>

                                <a href="assign-team-members-form.php?task_id=<?php echo htmlspecialchars($task['task_id']); ?>" class="btn">Assign Team Members</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>Contact us: <a href="mailto:email@company.com">email@company.com</a> | &copy; 2025 Task Allocator Pro</p>
    </footer>

</body>
</html>
