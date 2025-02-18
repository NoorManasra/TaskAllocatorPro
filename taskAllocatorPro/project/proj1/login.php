<?php
session_start();
include 'db.php.inc';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT user_id, username, password_hash, role, email FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];  
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; 
                $_SESSION['email'] = $user['email'];

                if ($user['role'] == 'Manager') {
                    header("Location: manager.html");
                } elseif ($user['role'] == 'Project Leader') {
                    header("Location: project_leader_dashboard.html");
                } else {
                    header("Location: team_member_dashboard.html");
                }
                exit();
            } else {
                $error_message = "Invalid username or password. Please try again.";
            }
        } else {

            $error_message = "Invalid username or password. Please try again.";
        }
    } catch (PDOException $e) {
       
        $error_message = "Error connecting to the database: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
    <div class="login-container">
        <h2>Login to Task Allocator Pro</h2>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="user_info.php">Sign up here</a></p>   
         <?php include 'footer.php'; ?>
       
    </div>
    
</body>
</html>
