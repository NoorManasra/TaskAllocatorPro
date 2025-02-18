<?php
session_start();

if (!isset($_SESSION['full_name']) || !isset($_SESSION['username'])) {
    header('Location: user_info.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php.inc'; 

    $full_name = $_SESSION['full_name'];
    $address_flat_no = $_SESSION['address_flat_no'];
    $address_street = $_SESSION['address_street'];
    $address_city = $_SESSION['address_city'];
    $address_country = $_SESSION['address_country'];
    $dob = $_SESSION['dob'];
    $id_number = $_SESSION['id_number'];
    $email = $_SESSION['email'];
    $telephone = $_SESSION['telephone'];
    $role = $_SESSION['role'];
    $qualification = $_SESSION['qualification'];
    $skills = $_SESSION['skills'];
    $username = $_SESSION['username'];
    $password_hash = $_SESSION['password'];

    $user_id = rand(1000000000, 9999999999);  

    try {
        
        $sql = "INSERT INTO users ( full_name, address_flat_no, address_street, address_city, address_country, date_of_birth, id_number, email, telephone, role, qualification, skills, username, password_hash) 
                VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([ 
            $full_name, $address_flat_no, $address_street, $address_city, 
            $address_country, $dob, $id_number, $email, $telephone, 
            $role, $qualification, $skills, $username, $password_hash
        ]);

        if ($role === 'Manager') {
            header('Location: manager.html');
        } elseif ($role === 'Project Leader') {
            header('Location: project_leader_dashboard.html');
        } elseif ($role === 'Team Member') {
            header('Location: team_member_dashboard.html');
        } else {
            echo "Role is not recognized. Please contact the administrator.";
        }

        session_unset();
        session_destroy();
        exit();
    } catch (PDOException $e) {
        echo "An error occurred: " . $e->getMessage();
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Your Information</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>
<div class="signup-container">
    <h3>Review your information</h3>
    <form action="step3.php" method="POST">
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
        <p><strong>Address:</strong> 
            <?php echo htmlspecialchars($_SESSION['address_flat_no'] . ", " . $_SESSION['address_street'] . ", " . $_SESSION['address_city'] . ", " . $_SESSION['address_country']); ?>
        </p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($_SESSION['dob']); ?></p>
        <p><strong>ID Number:</strong> <?php echo htmlspecialchars($_SESSION['id_number']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Telephone:</strong> <?php echo htmlspecialchars($_SESSION['telephone']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        <p><strong>Qualification:</strong> <?php echo htmlspecialchars($_SESSION['qualification']); ?></p>
        <p><strong>Skills:</strong> <?php echo htmlspecialchars($_SESSION['skills']); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <button type="submit">Confirm</button>
    </form>
    <?php include 'footer.php'; ?>
</div>
</body>
</html>
