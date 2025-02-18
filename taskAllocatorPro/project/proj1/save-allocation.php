<?php
session_start();
include 'db.php.inc';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'] ?? null;
    $team_leader_id = $_POST['user_id'] ?? null;

    if (!$project_id || !$team_leader_id) {
        die("Missing data.");
    }

    try {
        $query = "UPDATE project SET team_leader_id = :team_leader_id WHERE project_id = :project_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':user_id' => $team_leader_id,
            ':project_id' => $project_id
        ]);

        $_SESSION['allocated_project'] = $project_id;
        $_SESSION['allocated_leader'] = $team_leader_id;

        echo "Team Leader successfully allocated to project!";
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
