<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$email = $_SESSION['email'];

if ($role == 'Manager') {
    $userPhoto = 'manager icon.png'; 
} elseif ($role == 'Project Leader') {
    $userPhoto = 'projectleader-icon.webp'; 
} else {
    $userPhoto = 'team-members-icon.jpg'; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <div style="max-width: 400px; margin: 20px auto; text-align: center;">
        <img src="<?php echo $userPhoto; ?>" alt="User Photo" style="width: 100px; height: 100px; border-radius: 50%;">
        <h1><?php echo $username; ?></h1>
        <p><strong>Role:</strong> <?php echo $role; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <a href="logout.php" style="text-decoration: none; color: red;">Logout</a>
    </div>
</body>
</html>
