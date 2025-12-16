<?php
header('Content-Type: text/plain; charset=utf-8');

// Log para debug
error_log("POST recebido: " . print_r($_POST, true));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    echo "ERROR|Não autorizado";
    exit();
}

// Verifica se os parâmetros foram enviados
if (!isset($_POST['id_cartao']) || !isset($_POST['status'])) {
    echo "ERROR|Parâmetros inválidos - id_cartao: " . (isset($_POST['id_cartao']) ? $_POST['id_cartao'] : 'não enviado') . ", status: " . (isset($_POST['status']) ? $_POST['status'] : 'não enviado');
    exit();
}

$id_cartao = intval($_POST['id_cartao']);
$novo_status = intval($_POST['status']);

error_log("ID: $id_cartao, Novo Status: $novo_status");

// Valida o status (deve ser 0 ou 1)
if ($novo_status !== 0 && $novo_status !== 1) {
    echo "ERROR|Status inválido: $novo_status";
    exit();
}

$host = '127.0.0.1';
$port = '3307';
$db = 'easypark';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Atualizar o status do cartão
    $stmt = $pdo->prepare("
        UPDATE cartoes 
        SET ativo_cartao = :status 
        WHERE id_cartao = :id
    ");

    $stmt->execute([
        ':status' => $novo_status,
        ':id' => $id_cartao
    ]);

    $status_texto = $novo_status === 1 ? 'ativado' : 'desativado';
    echo "SUCCESS|Cartão " . $status_texto . " com sucesso";

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>