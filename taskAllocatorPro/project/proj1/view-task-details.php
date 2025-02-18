<?php
include('db.php.inc');

$taskID = $_GET['task_id'] ?? '';

try {
    // Query to get task details
    $taskQuery = "SELECT tasks.task_id, tasks.task_name, tasks.description, project.project_title, 
                         tasks.start_date, tasks.end_date, tasks.status, tasks.priority, tasks.effort
                  FROM tasks
                  JOIN project ON tasks.project_id = project.project_id
                  WHERE tasks.task_id = :task_id";

    $taskStmt = $pdo->prepare($taskQuery);
    $taskStmt->execute([':task_id' => $taskID]);
    $taskDetails = $taskStmt->fetch(PDO::FETCH_ASSOC);

    // Query to get team members for the task
    $teamQuery = "SELECT tta.user_id, tta.role, tta.contribution_percentage, tta.start_date, tta.status,
                         users.full_name, users.email
                  FROM task_team_assignments tta
                  JOIN users ON tta.user_id = users.user_id
                  WHERE tta.task_id = :task_id";

    $teamStmt = $pdo->prepare($teamQuery);
    $teamStmt->execute([':task_id' => $taskID]);
    $assignments = $teamStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
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
            <li><a href="completed-tasks.php" class="nav-link">Completed Tasks</a></li>
        </ul>
    </div>

    <main class="task-details-container">
        <!-- Part A: Task Details -->
        <div class="task-details">
            <h2>Task Details</h2>
            <?php if ($taskDetails): ?>
                <div class="task-info">
                    <p><strong>Task ID:</strong> <?= htmlspecialchars($taskDetails['task_id']) ?></p>
                    <p><strong>Task Name:</strong> <?= htmlspecialchars($taskDetails['task_name']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($taskDetails['description']) ?></p>
                    <p><strong>Project:</strong> <?= htmlspecialchars($taskDetails['project_title']) ?></p>
                    <p><strong>Start Date:</strong> <?= htmlspecialchars($taskDetails['start_date']) ?></p>
                    <p><strong>End Date:</strong> <?= htmlspecialchars($taskDetails['end_date']) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($taskDetails['status']) ?></p>
                    <p><strong>Priority:</strong> <?= htmlspecialchars($taskDetails['priority']) ?></p>
                    <p><strong>Completion Percentage:</strong> <?= htmlspecialchars($taskDetails['effort']) ?>%</p>
                </div>
            <?php else: ?>
                <p>No task details found for this ID.</p>
            <?php endif; ?>
        </div>

        <!-- Part B: Team Members Table -->
        <div class="team-members">
            <h2>Team Members</h2>
            <?php if (count($assignments) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Contribution (%)</th>
                            <th>Start Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $member): ?>
                            <tr>
                                <td>
    <img src="team-members-icon.jpg" alt="Team Member Icon" style="width: 40px; height: 40px;">
</td>

                                <td><?= htmlspecialchars($member['full_name']) ?></td>
                                <td><?= htmlspecialchars($member['email']) ?></td>
                                <td><?= htmlspecialchars($member['role']) ?></td>
                                <td><?= htmlspecialchars($member['contribution_percentage']) ?>%</td>
                                <td><?= htmlspecialchars($member['start_date']) ?></td>
                                <td><?= htmlspecialchars($member['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No team members assigned to this task.</p>
            <?php endif; ?>
        </div>
    </main>
</main>
    <?php include 'footer.php'; ?>
</body>
</html>
