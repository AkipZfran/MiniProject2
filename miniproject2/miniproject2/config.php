<?php
$host = "localhost";
$dbname = "pcrs_db";
$username = "root";
$password = ""; // Default XAMPP password is empty

try {
    // Using PDO for secure Prepared Statements [cite: 122]
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>