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

    // Buscar todos os formulários com informações do utilizador
    $stmt = $pdo->query("
        SELECT 
            f.id_form,
            f.avaliacao_form,
            f.mensagem_form,
            f.data_hora_form,
            u.nome_utilizador,
            u.numero_utilizador
        FROM formulario f
        INNER JOIN utilizadores u ON f.id_utilizador = u.id_utilizador
        ORDER BY f.data_hora_form DESC
    ");
    
    $formularios = $stmt->fetchAll();
    
    // Retorna em formato JSON
    echo "SUCCESS|" . json_encode($formularios, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>