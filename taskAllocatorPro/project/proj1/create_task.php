<?php
session_start();
include 'db.php.inc';

// التحقق من أن المستخدم قام بتسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // استرجاع user_id من الجلسة

// معالجة إضافة المهمة عند إرسال الفورم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $project_id = $_POST['project_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $effort = $_POST['effort'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    // التحقق من صحة الحقول
    if (empty($task_name) || empty($description) || empty($start_date) || empty($end_date) || empty($effort) || empty($status) || empty($priority)) {
        $error = "All fields must be filled out!";
    } elseif ($start_date > $end_date) {
        $error = "Start date cannot be later than end date.";
    } else {
        try {
            // إدخال المهمة في قاعدة البيانات
            $query = "INSERT INTO tasks (task_name, description, project_id, start_date, end_date, effort, status, priority)
                      VALUES (:task_name, :description, :project_id, :start_date, :end_date, :effort, :status, :priority)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':task_name' => $task_name,
                ':description' => $description,
                ':project_id' => $project_id,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':effort' => $effort,
                ':status' => $status,
                ':priority' => $priority,
            ]);

            $success = "Task '{$task_name}' successfully created!";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// استعلام لاختيار المشاريع التي ينتمي إليها المستخدم بناءً على user_id
$query = "SELECT p.project_id, p.project_title 
          FROM project p
          JOIN project_team_leaders ptl ON ptl.project_id = p.project_id
          WHERE ptl.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// إذا لم يتم العثور على مشاريع للمستخدم
if (empty($projects)) {
    $error = "No projects found for this user.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>
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

    <!-- Navigation Bar -->
    <div class="nav-bar">
        <ul>
            <li><a href="view_task.php" class="nav-link">View Tasks</a></li>
            <li><a href="allocate_team_member.php" class="nav-link active">Assign Team Members</a></li>

        </ul>
    </div>

    <!-- Main Section -->
    <div class="main-section">
        <h2>Create Task</h2>
       
        <form method="post">
            <div class="form-group">
                <label for="task_name">Task Name</label>
                <input type="text" id="task_name" name="task_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="project_id">Project</label>
                <select id="project_id" name="project_id" required>
                    <option value="">Select Project</option>
                    <?php foreach ($projects as $project): ?>
                        <option value="<?php echo htmlspecialchars($project['project_id']); ?>"><?php echo htmlspecialchars($project['project_title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
            <div class="form-group">
                <label for="effort">Effort (man-months)</label>
                <input type="number" id="effort" name="effort" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <button type="submit" class="btn">Create Task</button>
        </form>
        
    <?php if (!empty($error)) : ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif (!empty($success)) : ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    
    </div>

    <!-- Footer -->
    <footer>
        <p>Contact us: <a href="mailto:email@company.com">email@company.com</a> | © 2025 Task Allocator Pro</p>
    </footer>

</body>
</html>
