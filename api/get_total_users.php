<?php

$host = 'localhost';
$db   = 'easypark';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    // Conta quantos utilizadores existem na tabela
    $stmt = $pdo->query("SELECT COUNT(*) FROM utilizadores");
    $total = $stmt->fetchColumn();

    // Retorna: SUCCESS|150
    echo "SUCCESS|" . $total;

} catch (Exception $e) {
    echo "ERROR|0";
}
?>