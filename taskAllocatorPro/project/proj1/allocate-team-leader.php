<?php
include 'db.php.inc'; 

try {
    $query_projects = "SELECT project_id,project_title, start_date, end_date 
                       FROM project 
                      ";
    $stmt_projects = $pdo->query($query_projects);
    $projects = $stmt_projects->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
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
            <li><a href="manage-users.html" class="nav-link">Manage Users</a></li>
        </ul>
    </div>
     <div class="main-section">
    <main>
        <table>
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['project_id']); ?></td>
                        <td><?php echo htmlspecialchars($project['project_title']); ?></td>
                        <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                        <td>
                            <a href="assign-team-leader.php?project_id=<?php echo $project['project_id']; ?>">Allocate Team Leader</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    </div>
</body>  
    <footer>
        <p>Contact us: <a href="mailto:email@company.com">email@company.com</a> | &copy; 2025 Task Allocator Pro</p>
    </footer>
</html>
