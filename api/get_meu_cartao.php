<?php
header('Content-Type: text/plain; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_utilizador'])) {
    echo "ERROR|Utilizador não autenticado";
    exit();
}

$id_utilizador = $_SESSION['id_utilizador'];

// Configuração DB
$host = '127.0.0.1';
$db   = 'easypark';
$user = 'root';
$pass = '';
$port = '3307'; // Muda para '3307' se necessário

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Query para buscar TODOS os cartões e os seus tipos
    $sql = "
        SELECT 
            c.numero_cartao,
            c.ativo_cartao,
            c.data_registo_cartao,
            tc.tipo_cartao
        FROM cartoes c
        INNER JOIN tipo_cartao tc ON c.id_tipo_cartao = tc.id_tipo_cartao
        WHERE c.id_utilizador = :id_utilizador
        ORDER BY c.data_registo_cartao DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_utilizador' => $id_utilizador]);
    $cartoes = $stmt->fetchAll();

    if (!$cartoes) {
        echo "ERROR|Não tem cartões associados.";
        exit();
    }

    $listaOutput = [];

    foreach ($cartoes as $cartao) {
        // Formato linha: NUMERO|ATIVO|DATA|TIPO
        $linha = $cartao['numero_cartao'] . '|' . 
                 $cartao['ativo_cartao'] . '|' . 
                 $cartao['data_registo_cartao'] . '|' . 
                 $cartao['tipo_cartao'];
        
        $listaOutput[] = $linha;
    }

    // Retorna: SUCCESS | cartao1;cartao2;cartao3...
    echo "SUCCESS|" . implode(';', $listaOutput);

} catch (PDOException $e) {
    echo "ERROR|Erro DB: " . $e->getMessage();
}
?>