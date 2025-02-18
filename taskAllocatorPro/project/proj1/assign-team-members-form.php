<?php
session_start();
include 'db.php.inc';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$task_id = isset($_GET['task_id']) ? $_GET['task_id'] : null;
if ($task_id === null) {
    header("Location: assign-team-members-form.php"); 
    exit();
}

$query = "SELECT task_id, task_name, start_date, end_date, status FROM tasks WHERE task_id = :task_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':task_id' => $task_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: assign-team-members-form.php"); 
    exit();
}

$task_start_date = $task['start_date'];

$query = "SELECT u.user_id, u.full_name FROM users u WHERE u.role != 'Manager' AND u.role != 'Project Leader'";
$stmt = $pdo->prepare($query);
$stmt->execute();
$team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_member_id = $_POST['team_member'];
    $role = $_POST['role'];
    $contribution = $_POST['contribution'];
    $start_date = $_POST['start_date'];

    if (empty($team_member_id) || empty($role) || empty($contribution) || empty($start_date)) {
        $error = "All fields must be filled out!";
    } else {
        $query = "SELECT SUM(contribution_percentage) as total_contribution FROM task_team_assignments WHERE task_id = :task_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':task_id' => $task_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_contribution = $result['total_contribution'] + $contribution;

        if ($total_contribution > 100) {
            $error = "The total contribution percentage for all assigned members cannot exceed 100%.";
        } elseif (strtotime($start_date) < strtotime($task_start_date)) {
            $error = "Team member's start date cannot precede the task's start date.";
        } else {
            try {
                $query = "INSERT INTO task_team_assignments (task_id, user_id, role, contribution_percentage, start_date) 
                          VALUES (:task_id, :user_id, :role, :contribution, :start_date)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':task_id' => $task_id,
                    ':user_id' => $team_member_id,
                    ':role' => $role,
                    ':contribution' => $contribution,
                    ':start_date' => $start_date,
                ]);
                $success = "Team member successfully assigned to Task {$task['task_name']} as {$role}.";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Team Member to Task</title>
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
            <li><a href="view_task.php" class="nav-link">View Tasks</a></li>
            <li><a href="allocate_team_member.php" class="nav-link active">Assign Team Members</a></li>
        </ul>
    </div>

    <div class="main-section">
        <h2>Assign Team Member to Task</h2>

        <h3>Task Details</h3>
        <p><strong>Task Name:</strong> <?php echo htmlspecialchars($task['task_name']); ?></p>
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($task['start_date']); ?></p>
        <p><strong>End Date:</strong> <?php echo htmlspecialchars($task['end_date']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>

        <form method="post">
            <div class="form-group">
                <label for="team_member">Team Member</label>
                <select id="team_member" name="team_member" required>
                    <option value="">Select Team Member</option>
                    <?php foreach ($team_members as $member): ?>
                        <option value="<?php echo htmlspecialchars($member['user_id']); ?>"><?php echo htmlspecialchars($member['full_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Developer">Developer</option>
                    <option value="Designer">Designer</option>
                    <option value="Tester">Tester</option>
                    <option value="Analyst">Analyst</option>
                    <option value="Support">Support</option>
                </select>
            </div>

            <div class="form-group">
                <label for="contribution">Contribution Percentage</label>
                <input type="number" id="contribution" name="contribution" min="1" max="100" required>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <button type="submit" class="btn">Confirm Allocation</button>
        </form> 

        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
    </div>
<main/>

    <?php include 'footer.php'; ?>
</body>
</html>
