<?php
session_start(); // Start the session
include('db.php.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskID = $_POST['task_id'] ?? '';
    $taskName = $_POST['task_name'] ?? '';
    $projectName = $_POST['project_name'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $status = $_POST['status'] ?? '';

    try {
        $query = "SELECT tasks.task_id, tasks.task_name, project.project_title, tasks.status, tasks.priority, tasks.start_date, tasks.end_date, tasks.effort
                  FROM tasks
                  JOIN project ON tasks.project_id = project.project_id
                  WHERE 1 = 1";

        $params = [];

        if (!empty($taskID)) {
            $query .= " AND tasks.task_id LIKE :task_id";
            $params[':task_id'] = "%$taskID%";
        }

        if (!empty($taskName)) {
            $query .= " AND tasks.task_name LIKE :task_name";
            $params[':task_name'] = "%$taskName%";
        }

        if (!empty($projectName)) {
            $query .= " AND project.project_title LIKE :project_name";
            $params[':project_name'] = "%$projectName%";
        }

        if (!empty($priority)) {
            $query .= " AND tasks.priority = :priority";
            $params[':priority'] = $priority;
        }

        if (!empty($status)) {
            $query .= " AND tasks.status = :status";
            $params[':status'] = $status;
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Update Task Progress</title>
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

        <section class="main-section">
            <h2>Search Tasks</h2>
            <form method="POST" action="">
                <input type="text" name="task_id" placeholder="Task ID" value="<?= htmlspecialchars($taskID ?? '') ?>">
                <input type="text" name="task_name" placeholder="Task Name" value="<?= htmlspecialchars($taskName ?? '') ?>">
                <input type="text" name="project_name" placeholder="Project Name" value="<?= htmlspecialchars($projectName ?? '') ?>">
                <select name="priority">
                    <option value="">Priority</option>
                    <option value="Low" <?= (isset($priority) && $priority === 'Low') ? 'selected' : '' ?>>Low</option>
                    <option value="Medium" <?= (isset($priority) && $priority === 'Medium') ? 'selected' : '' ?>>Medium</option>
                    <option value="High" <?= (isset($priority) && $priority === 'High') ? 'selected' : '' ?>>High</option>
                </select>
                <select name="status">
                    <option value="">Status</option>
                    <option value="Pending" <?= (isset($status) && $status === 'Pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= (isset($status) && $status === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                    <option value="Completed" <?= (isset($status) && $status === 'Completed') ? 'selected' : '' ?>>Completed</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </section>

        <?php if (isset($tasks) && count($tasks) > 0): ?>
            <section class="main-section">
                <h2>Search Results</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Project Name</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['task_id']) ?></td>
                                <td><?= htmlspecialchars($task['task_name']) ?></td>
                                <td><?= htmlspecialchars($task['project_title']) ?></td>
                                <td><?= htmlspecialchars($task['status']) ?></td>
                                <td><?= htmlspecialchars($task['priority']) ?></td>
                                <td><?= htmlspecialchars($task['start_date']) ?></td>
                                <td><?= htmlspecialchars($task['end_date']) ?></td>
                                <td><?= htmlspecialchars($task['effort']) ?>%</td>
                                <td>
                                    <a href="update-task.php?task_id=<?= urlencode($task['task_id']) ?>">Update</a>
                                    <a href="view-task-details.php?task_id=<?= urlencode($task['task_id']) ?>">View Task Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No tasks found matching your criteria.</p>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>