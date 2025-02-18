<?php
session_start();
include 'db.php.inc';

try {
    $query = "SELECT * FROM project";
    $stmt = $pdo->query($query);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Projects</title>
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
    <li><a href="allocate-team-leader.php" class="nav-link">Allocate Team Leader</a></li>

        <li><a href="add_project.php" class="nav-link">Add Project</a></li>
        <li><a href="manage_users.php" class="nav-link">Manage Users</a></li>
    </ul>
</div>

<div class="main-section">
        <h2>All Projects</h2>

        <?php if ($projects): ?>
            <table>
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Project Title</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Customer Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($project['project_id']); ?></td>
                            <td><?php echo htmlspecialchars($project['project_title']); ?></td>
                            <td><?php echo htmlspecialchars($project['project_description']); ?></td>
                            <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($project['customer_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No projects found.</p>
        <?php endif; ?>
    </main>
</div>
    <?php include 'footer.php'; ?>


</body>
</html>
