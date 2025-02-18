<?php
session_start();
include 'db.php.inc';

try {
    $query = "SELECT * FROM users";
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $full_name = $_POST['full_name'];
    $address_flat_no = $_POST['address_flat_no'];
    $address_street = $_POST['address_street'];
    $address_city = $_POST['address_city'];
    $address_country = $_POST['address_country'];
    $date_of_birth = $_POST['date_of_birth'];
    $id_number = $_POST['id_number'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];
    $qualification = $_POST['qualification'];
    $skills = $_POST['skills'];
    $username = $_POST['username'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    try {
        $query = "INSERT INTO users (full_name, address_flat_no, address_street, address_city, address_country, date_of_birth, id_number, email, telephone, role, qualification, skills, username, password_hash) VALUES (:full_name, :address_flat_no, :address_street, :address_city, :address_country, :date_of_birth, :id_number, :email, :telephone, :role, :qualification, :skills, :username, :password_hash)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':full_name' => $full_name,
            ':address_flat_no' => $address_flat_no,
            ':address_street' => $address_street,
            ':address_city' => $address_city,
            ':address_country' => $address_country,
            ':date_of_birth' => $date_of_birth,
            ':id_number' => $id_number,
            ':email' => $email,
            ':telephone' => $telephone,
            ':role' => $role,
            ':qualification' => $qualification,
            ':skills' => $skills,
            ':username' => $username,
            ':password_hash' => $password_hash
        ]);

        $_SESSION['success_message'] = "User added successfully.";
        header("Location: manage_users.php"); // إعادة التوجيه بعد الإضافة
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error adding user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        <li><a href="view-projects.php" class="nav-link">View Projects</a></li>
    </ul>
</div>

<!-- Main Section -->
<div class="main-section">
        <h2>Manage Users</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php elseif (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Display Users Table -->
        <?php if ($users): ?>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a href="delete-users.php?delete_id=<?php echo $user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                | 
                                <a href="edit-user.php?user_id=<?php echo $user['user_id']; ?>">Update</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>

        <!-- Add New User Form -->
        <h3>Add New User</h3>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="address_flat_no" placeholder="Address Flat No">
            <input type="text" name="address_street" placeholder="Address Street">
            <input type="text" name="address_city" placeholder="Address City">
            <input type="text" name="address_country" placeholder="Address Country">
            <input type="date" name="date_of_birth" required>
            <input type="text" name="id_number" placeholder="ID Number" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="telephone" placeholder="Telephone">
            <input type="text" name="role" placeholder="Role" required>
            <input type="text" name="qualification" placeholder="Qualification">
            <textarea name="skills" placeholder="Skills"></textarea>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </main>
</div>

    <?php include 'footer.php'; ?>

</body>
</html>
