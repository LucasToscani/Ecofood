<?php
// db_connection.php

$host     = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_DATABASE') ?: 'EcoFood';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$port     = getenv('DB_PORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
