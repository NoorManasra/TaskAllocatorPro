<?php
session_start();
include 'db.php.inc';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // استرجاع user_id من الجلسة

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    try {
        $query = "
        SELECT 
            t.task_id,
            t.task_name,
            t.description,
            t.start_date AS task_start_date,
            t.end_date AS task_end_date,
            t.priority,
            t.status
        FROM tasks t
        WHERE t.task_id = :task_id AND t.project_id IN (
            SELECT project_id FROM project_team_leaders WHERE user_id = :user_id
        )";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([':task_id' => $task_id, ':user_id' => $user_id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            header("Location: view_task.php?error=Task not found or you do not have permission to edit it.");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
} else {
    header("Location: view_task.php?error=No task ID provided.");
    exit();
}

// تحديث البيانات عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    try {
        $update_query = "
        UPDATE tasks
        SET task_name = :task_name, description = :description, start_date = :start_date,
            end_date = :end_date, priority = :priority, status = :status
        WHERE task_id = :task_id AND project_id IN (
            SELECT project_id FROM project_team_leaders WHERE user_id = :user_id
        )";
        
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([
            ':task_name' => $task_name,
            ':description' => $description,
            ':start_date' => $start_date,
            ':end_date' => $end_date,
            ':priority' => $priority,
            ':status' => $status,
            ':task_id' => $task_id,
            ':user_id' => $user_id
        ]);

       
        header("Location: view_task.php?success=Task updated successfully");
        exit();
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
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
            <li><a href="create_task.php" class="nav-link">Create Task</a></li>
            <li><a href="allocate_team_member.php" class="nav-link">Assign Team Members</a></li>
        </ul>
    </div>

    <div class="main-section">
        <h2>Edit Task</h2>

        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="edit_task.php?task_id=<?php echo htmlspecialchars($task['task_id']); ?>" method="POST">
            <div class="form-group">
                <label for="task_name">Task Name:</label>
                <input type="text" id="task_name" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($task['task_start_date']); ?>" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($task['task_end_date']); ?>" required>
            </div>

            <div class="form-group">
                <label for="priority">Priority:</label>
                <select id="priority" name="priority" required>
                    <option value="Low" <?php echo ($task['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                    <option value="Medium" <?php echo ($task['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                    <option value="High" <?php echo ($task['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Not Started" <?php echo ($task['status'] == 'Not Started') ? 'selected' : ''; ?>>Not Started</option>
                    <option value="In Progress" <?php echo ($task['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>

            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
<main/>
      <?php include 'footer.php'; ?>


</body>
</html>
