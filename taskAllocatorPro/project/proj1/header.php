<?php
session_start();
?>

<header style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: #1e3d58; color: white;">
    <!-- Logo or Website Name -->
    <div style="font-size: 20px; font-weight: bold;">My Website</div>
    
    <!-- User Links -->
    <div>
        <?php if (isset($_SESSION['username'])): ?>
            <!-- User Profile -->
            <a href="profile.php" style="margin-right: 15px; text-decoration: none; color: white;">
                <img src="<?php echo $_SESSION['user_photo']; ?>" alt="User Photo" style="width: 30px; height: 30px; border-radius: 50%; vertical-align: middle;">
                <?php echo $_SESSION['username']; ?>
            </a>
            <!-- Logout -->
            <a href="logout.php" style="text-decoration: none; color: white;">Logout</a>
        <?php else: ?>
            <!-- Login & Sign-Up -->
            <a href="login.php" style="margin-right: 15px; text-decoration: none; color: white;">Login</a>
            <a href="signup.php" style="text-decoration: none; color: white;">Sign-Up</a>
        <?php endif; ?>
    </div>
</header>
