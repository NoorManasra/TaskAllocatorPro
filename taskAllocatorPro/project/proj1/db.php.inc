<?php
// Database connection details
$host = 'localhost';
$dbname = '';
$username = '';
$password = '';
$port = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Remove any debug output to avoid "headers already sent" issues
    // echo "Database connection successful!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
