<?php
$host = 'localhost';
$dbname = 'student_db';
$username = 'root';
$password = '';

try {
    // Connect without specifying the database first
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Auto-create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");
    
    // Auto-create table if missing
    $stmt = $pdo->query("SHOW TABLES LIKE 'students'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE TABLE students (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            year INT NOT NULL,
            password VARCHAR(255) NOT NULL,
            security_question VARCHAR(255) NOT NULL,
            security_answer VARCHAR(255) NOT NULL
        )");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
