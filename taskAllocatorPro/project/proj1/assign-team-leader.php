<?php
session_start();
include 'db.php.inc';

$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    die("Invalid project ID.");
}

try {
    $query_project = "SELECT * FROM project WHERE project_id = :project_id";
    $stmt_project = $pdo->prepare($query_project);
    $stmt_project->execute([':project_id' => $project_id]);
    $project = $stmt_project->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        die("Project not found.");
    }

    $query_leaders = "SELECT user_id, full_name FROM users WHERE role = 'Project Leader'";
    $stmt_leaders = $pdo->query($query_leaders);
    $leaders = $stmt_leaders->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$success_message = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_leader_id = $_POST['team_leader_id'] ?? null;

    if (!$team_leader_id) {
        $error_message = "Please select a valid Team Leader.";
    } else {
        try {
            // إدخال البيانات في جدول project_team_leaders
            $query = "INSERT INTO project_team_leaders (project_id, user_id) VALUES (:project_id, :user_id)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':project_id' => $project_id,
                ':user_id' => $team_leader_id
            ]);

            $success_message = "Team Leader successfully allocated to Project " . htmlspecialchars($project_id);
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
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
        <li><a href="add_project.php" class="nav-link">Add Project</a></li>
        <li><a href="view-projects.php" class="nav-link">View Projects</a></li>
        <li><a href="manage_users.php" class="nav-link">Manage Users</a></li>
    </ul>
</div>

<div class="main-section">
    <main>
        <form method="POST">
            <fieldset>
                <legend>Project Details</legend>
                <p><strong>Project ID:</strong> <?php echo htmlspecialchars($project['project_id']); ?></p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($project['project_title']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($project['project_description']); ?></p>
            </fieldset>

            <fieldset>
                <legend>Select Team Leader</legend>
                <label for="team_leader">Team Leader:</label>
                <select name="team_leader_id" id="team_leader" required>
                    <option value="">-- Select Team Leader --</option>
                    <?php foreach ($leaders as $leader): ?>
                        <option value="<?php echo htmlspecialchars($leader['user_id']); ?>">
                            <?php echo htmlspecialchars($leader['full_name']) . ' - ' . htmlspecialchars($leader['user_id']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>">
            <button type="submit">Confirm Allocation</button>
        </form>

        <?php if ($success_message): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php elseif ($error_message): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<footer>
    <p>Contact us: <a href="mailto:email@company.com">email@company.com</a> | &copy; 2025 Task Allocator Pro</p>
</footer>

</body>
</html>
