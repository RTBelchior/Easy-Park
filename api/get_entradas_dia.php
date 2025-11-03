<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    
    // Query para contar entradas de hoje
    $sql = "
        SELECT COUNT(*) as total_entradas
        FROM historico_acesso
        WHERE tipo_acesso = 'entrada'
        AND DATE(data_hora) = CURDATE()
    ";
    
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'total_entradas' => (int)$row['total_entradas'],
            'data' => date('Y-m-d')
        ], JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception("Erro na query");
    }
    
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar entradas',
        'error_details' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>