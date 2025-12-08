<?php
header('Content-Type: text/plain; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    echo "ERROR|Não autorizado";
    exit();
}

$id_utilizador = $_SESSION['id_utilizador'];

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

    // Buscar TODOS os acessos do utilizador (sem limite)
    $stmt = $pdo->prepare("
        SELECT 
            a.tipo_acesso,
            a.data_hora_acesso,
            a.id_parque
        FROM acesso a
        INNER JOIN cartoes c ON a.id_cartao = c.id_cartao
        WHERE c.id_utilizador = :id_utilizador
        ORDER BY a.data_hora_acesso DESC
    ");
    
    $stmt->execute([':id_utilizador' => $id_utilizador]);
    $acessos = $stmt->fetchAll();

    echo "SUCCESS|" . json_encode($acessos, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>