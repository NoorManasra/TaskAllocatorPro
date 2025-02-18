<?php
session_start();

if (!isset($_SESSION['full_name'])) {
    header('Location: user_info.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (strlen($username) < 6 || strlen($username) > 13) {
        $error_message = "Username must be between 6 and 13 characters.";
    } elseif (strlen($password) < 8 || strlen($password) > 12) {
        $error_message = "Password must be between 8 and 12 characters.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);

        header('Location: step3.php');
        exit();
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
<div class="signup-container">
    <h1>E-Account Creation</h1>
<form action="step2.php" method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required><br>

    <button type="submit">Proceed to Confirmation</button>
</form>
        <?php include 'footer.php'; ?>
</div>


    </body>
    <html/>
<?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
