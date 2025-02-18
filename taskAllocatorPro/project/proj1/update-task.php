<?php
include('db.php.inc');

if (isset($_GET['task_id'])) {
    $taskID = $_GET['task_id'];

    try {
        $query = "SELECT * FROM tasks WHERE task_id = :task_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':task_id' => $taskID]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            echo "Task not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "No task selected.";
    exit;
}

$updateMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['task_name'];
    $projectID = $_POST['project_id'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $effort = $_POST['effort'];

    try {
        $updateQuery = "UPDATE tasks SET task_name = :task_name, project_id = :project_id, priority = :priority, status = :status, effort = :effort WHERE task_id = :task_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            ':task_name' => $taskName,
            ':project_id' => $projectID,
            ':priority' => $priority,
            ':status' => $status,
            ':effort' => $effort,
            ':task_id' => $taskID
        ]);
        $updateTeamQuery = "UPDATE task_team_assignments SET role = :role, contribution_percentage = :contribution_percentage, start_date = :start_date, status = :team_status WHERE task_id = :task_id AND user_id = :user_id";
        $updateTeamStmt = $pdo->prepare($updateTeamQuery);
        $updateTeamStmt->execute([
            
            ':task_id' => $taskID,
           
        ]);

        $updateMessage = "Task updated successfully.";
    } catch (PDOException $e) {
        $updateMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task</title>
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
            <li><a href="search-task.php" class="nav-link">Search Task</a></li>

        </ul>
    </div>
    <div class="main-section">
        <h2>Update Task</h2>
        <form method="POST" action="">
            <input type="text" name="task_name" value="<?= htmlspecialchars($task['task_name']) ?>" placeholder="Task Name" required>
            <input type="number" name="project_id" value="<?= htmlspecialchars($task['project_id']) ?>" placeholder="Project ID" required>
            <select name="priority" required>
                <option value="Low" <?= ($task['priority'] === 'Low') ? 'selected' : '' ?>>Low</option>
                <option value="Medium" <?= ($task['priority'] === 'Medium') ? 'selected' : '' ?>>Medium</option>
                <option value="High" <?= ($task['priority'] === 'High') ? 'selected' : '' ?>>High</option>
            </select>
            <select name="status" required>
                <option value="Pending" <?= ($task['status'] === 'Pending') ? 'selected' : '' ?>>Pending</option>
                <option value="In Progress" <?= ($task['status'] === 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= ($task['status'] === 'Completed') ? 'selected' : '' ?>>Completed</option>
            </select>
            <div class="range-slider">
                <label for="effort">Effort:</label>
                <input type="range" name="effort" id="effort" min="0" max="100" value="<?= htmlspecialchars($task['effort']) ?>" list="tickmarks" required>
                <output for="effort" name="effort_output"><?= htmlspecialchars($task['effort']) ?></output>
                <datalist id="tickmarks">
                    <option value="0" label="0%">
                    <option value="20">
                    <option value="40">
                    <option value="60">
                    <option value="80">
                    <option value="100" label="100%">
                </datalist>
            </div>
            <button type="submit">Update Task</button>
            <?php if ($updateMessage): ?>
                <p><?= $updateMessage ?></p>
            <?php endif; ?>
        </form>
    </main>
  </div>
     <?php include 'footer.php'; ?>


</body>
</html>
