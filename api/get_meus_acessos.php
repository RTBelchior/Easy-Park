<?php
header('Content-Type: text/plain; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    // Verificação de autenticação
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Não autenticado");
    }

    $id_utilizador = $_SESSION['id_utilizador'];

    // Conexão MySQLi
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        die("ERROR|Falha na conexão: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    // Buscar TODOS os acessos do utilizador
    $sql = "
        SELECT 
            a.tipo_acesso,
            a.data_hora_acesso,
            a.id_parque
        FROM acesso a
        INNER JOIN cartoes c ON a.id_cartao = c.id_cartao
        WHERE c.id_utilizador = ?
        ORDER BY a.data_hora_acesso DESC
    ";

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $result = $stmt->get_result();

        $acessos = [];
        while ($row = $result->fetch_assoc()) {
            $acessos[] = $row;
        }

        // Retorna: SUCCESS | JSON com os dados
        echo "SUCCESS|" . json_encode($acessos, JSON_UNESCAPED_UNICODE);
        
        $stmt->close();
    } else {
        echo "ERROR|Erro na preparação da consulta: " . $conn->error;
    }

    $conn->close();

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>