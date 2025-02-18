<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['full_name'] = $_POST['full_name'] ?? '';
    $_SESSION['address_flat_no'] = $_POST['address_flat_no'] ?? '';
    $_SESSION['address_street'] = $_POST['address_street'] ?? '';
    $_SESSION['address_city'] = $_POST['address_city'] ?? '';
    $_SESSION['address_country'] = $_POST['address_country'] ?? '';
    $_SESSION['dob'] = $_POST['dob'] ?? '';
    $_SESSION['id_number'] = $_POST['id_number'] ?? '';
    $_SESSION['email'] = $_POST['email'] ?? '';
    $_SESSION['telephone'] = $_POST['telephone'] ?? '';
    $_SESSION['role'] = $_POST['role'] ?? '';
    $_SESSION['qualification'] = $_POST['qualification'] ?? '';
    $_SESSION['skills'] = $_POST['skills'] ?? '';

    header('Location: step2.php');
    exit();
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>
<div class="signup-container">
    <h1>User Information</h1>
    <form action="user_info.php" method="POST">
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" width=10 required>
        </div>
        <div class="form-group">
            <label for="address_flat_no">Flat/House No:</label>
            <input type="text" id="address_flat_no" name="address_flat_no" placeholder="Flat/House number" required>
        </div>
        <div class="form-group">
            <label for="address_street">Street:</label>
            <input type="text" id="address_street" name="address_street" placeholder="Street name" required>
        </div>
        <div class="form-group">
            <label for="address_city">City:</label>
            <input type="text" id="address_city" name="address_city" placeholder="City name" required>
        </div>
        <div class="form-group">
            <label for="address_country">Country:</label>
            <input type="text" id="address_country" name="address_country" placeholder="Country name" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>
        </div>
        <div class="form-group">
            <label for="id_number">ID Number:</label>
            <input type="text" id="id_number" name="id_number" placeholder="National ID number" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="example@mail.com" required>
        </div>
        <div class="form-group">
            <label for="telephone">Telephone:</label>
            <input type="text" id="telephone" name="telephone" placeholder="Enter phone number" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Manager">Manager</option>
                <option value="Project Leader">Project Leader</option>
                <option value="Team Member">Team Member</option>
            </select>
        </div>
        <div class="form-group">
            <label for="qualification">Qualification:</label>
            <input type="text" id="qualification" name="qualification" placeholder="e.g., Bachelor's in IT" required>
        </div>
        <div class="form-group">
            <label for="skills">Skills:</label>
            <input type="text" id="skills" name="skills" placeholder="e.g., PHP, JavaScript" required>
        </div>
        <button type="submit">Submit</button>
    </form>
      <?php include 'footer.php'; ?>
</div>
</body>
</html>
