<?php
header('Content-Type: text/plain; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    echo "ERROR|Não autorizado";
    exit();
}

$host = '127.0.0.1';
$port = '3307';
$db   = 'easypark';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Buscar todos os cartões com informações do utilizador
    $stmt = $pdo->query("
        SELECT 
            c.id_cartao,
            c.numero_cartao,
            c.ativo_cartao,
            c.data_registo_cartao,
            c.id_tipo_cartao,
            u.nome_utilizador,
            u.numero_utilizador
        FROM cartoes c
        INNER JOIN utilizadores u ON c.id_utilizador = u.id_utilizador
        ORDER BY u.nome_utilizador ASC
    ");
    
    $cartoes = $stmt->fetchAll();
    
    // Retorna em formato JSON
    echo "SUCCESS|" . json_encode($cartoes, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>