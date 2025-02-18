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
        p.project_title,
        t.task_id,
        t.task_name,
        t.description,
        t.start_date AS task_start_date,
        t.end_date AS task_end_date,
        t.priority,
        t.status,
        u.full_name AS team_member_name,
        ta.role AS team_role,
        ta.contribution_percentage AS task_contribution
    FROM tasks t
    JOIN project p ON t.project_id = p.project_id
    LEFT JOIN task_team_assignments ta ON t.task_id = ta.task_id
    LEFT JOIN users u ON ta.user_id = u.user_id
    WHERE t.project_id IN (
        SELECT project_id FROM project_team_leaders WHERE user_id = :user_id
    )
    ORDER BY p.project_title, t.task_name";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

    if (empty($tasks)) {
        $error = "No tasks found for your assigned projects.";
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
    <title>View and Manage Tasks</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>

    <!-- Header -->
    <header>
        <div class="header-title">Task Allocator Pro</div>
        <div class="user-profile">
            <a href="profile.php" class="profile-link">My Profile</a>
        </div>
    </header>
<main>
    <div class="nav-bar">
        <ul>
            <li><a href="create_task.php" class="nav-link">Create Task</a></li>
            <li><a href="allocate_team_member.php" class="nav-link">Assign Team Members</a></li>
        </ul>
    </div>

    <!-- Main Section -->
    <div class="main-section">
        <h2>View and Manage Tasks</h2>

        <?php if (isset($_GET['success'])): ?>
            <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php else : ?>
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
                        <th>Team Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $project_title => $project_tasks) : ?>
                        <?php foreach ($project_tasks as $task) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($project_title); ?></td>
                                <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td><?php echo htmlspecialchars($task['task_start_date']); ?></td>
                                <td><?php echo htmlspecialchars($task['task_end_date']); ?></td>
                                <td><?php echo htmlspecialchars($task['priority']); ?></td>
                                <td><?php echo htmlspecialchars($task['status']); ?></td>
                                <td>
                                    <ul>
                                        <?php
                                        $team_members = false; // Flag to track if there are any team members
                                        foreach ($project_tasks as $member) {
                                            if ($member['task_id'] == $task['task_id']) {
                                                $team_members = true; // If a team member is found
                                                echo "<li>" . htmlspecialchars($member['team_member_name'] ?? 'Not Assigned Yet!') . " (" . htmlspecialchars($member['team_role'] ?? 'Not Assigned Yet!') . "): " . htmlspecialchars($member['task_contribution'] ?? 'Not Assigned Yet!') . "%</li>";
                                                
                                            }
                                        }
                                        if (!$team_members) {
                                            // If no team members are assigned
                                            echo "<li>Not Assigned Yet!</li>";
                                        }
                                        ?>
                                    </ul>
                                </td>
                                <td>
                                    <!-- روابط تعديل وحذف -->
                                    <a href="edit_task.php?task_id=<?php echo htmlspecialchars($task['task_id']); ?>" class="btn">Edit</a>
                                    <a href="delete-task.php?task_id=<?php echo htmlspecialchars($task['task_id']); ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
      <?php include 'footer.php'; ?>


</body>
</html>
