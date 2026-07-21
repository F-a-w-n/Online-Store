<?php
// Original code by Fawn Barisic
// database connection handler

// initializes the PDO connection using credentials from config.php
require_once __DIR__ . '/config.php';

try {
    // create the PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // kill the script and display a user-friendly message
    die("Database connection failed. Please try again later.");
}
?>