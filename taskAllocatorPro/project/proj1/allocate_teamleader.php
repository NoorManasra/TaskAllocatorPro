<?php
// تضمين الاتصال بقاعدة البيانات
include('db.php.inc');

// جلب تفاصيل المشروع بناءً على project_id
$project_id = $_GET['project_id'];
$query = "SELECT * FROM project WHERE project_id = :project_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['project_id' => $project_id]);
$project = $stmt->fetch();

// جلب قائمة قادة الفرق
$leaders_query = "SELECT user_id, full_name FROM users WHERE role = 'Team Leader'";
$leaders = $pdo->query($leaders_query)->fetchAll();

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_leader_id = $_POST['team_leader_id'];
    
    // التحقق من اختيار قائد الفريق
    if (empty($team_leader_id)) {
        $message = "Please select a team leader.";
    } else {
        // تحديث المشروع بإضافة قائد الفريق
        $update_query = "UPDATE project SET team_leader_id = :team_leader_id WHERE project_id = :project_id";
        $stmt = $pdo->prepare($update_query);
        $stmt->execute(['team_leader_id' => $team_leader_id, 'project_id' => $project_id]);
        $message = "Team Leader successfully allocated to Project {$project_id}.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Team Leader</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>
    <header>
        <div class="header-title">Task Allocator Pro</div>
    </header>
<main>
    <div class="nav-bar">
        <ul>
            <li><a href="allocate-team-leader.php" class="nav-link">Allocate Team Leader</a></li>
            <li><a href="view-projects.php" class="nav-link">View Projects</a></li>
            <li><a href="manage-users.php" class="nav-link">Manage Users</a></li>
        </ul>
    </div>

    <section class="main-section">
        <h2>Allocate Team Leader to Project</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="allocate_teamleader.php?project_id=<?php echo $project_id; ?>" method="POST">
            <fieldset>
                <legend>Project Details</legend>
                <p>Project ID: <?php echo $project['project_id']; ?></p>
                <p>Project Title: <?php echo $project['project_title']; ?></p>
                <p>Project Description: <?php echo $project['project_description']; ?></p>
                <p>Customer Name: <?php echo $project['customer_name']; ?></p>
                <p>Total Budget: <?php echo $project['total_budget']; ?></p>
                <p>Start Date: <?php echo $project['start_date']; ?></p>
                <p>End Date: <?php echo $project['end_date']; ?></p>
            </fieldset>

            <fieldset>
                <legend>Select Team Leader</legend>
                <select name="team_leader_id" required>
                    <option value="">--Select Team Leader--</option>
                    <?php foreach ($leaders as $leader): ?>
                        <option value="<?php echo $leader['user_id']; ?>"><?php echo $leader['full_name']; ?> - <?php echo $leader['user_id']; ?></option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <button type="submit">Confirm Allocation</button>
        </form>
    </section>
    <main/>
     <?php include 'footer.php'; ?>
</body>
</html>
