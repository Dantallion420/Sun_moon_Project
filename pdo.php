<?php
$host = 'localhost'; // Your host (e.g., localhost)
$port = '8889'; // Your port number (if different from default)
$db = 'sunandmoon'; // Your database name
$user = 'root'; // Your database username
$password = 'root'; // Your database password

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>