<?php
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    
    if ($conn->connect_error) {
        die("ERRO|Falha na conexão");
    }
    
    $conn->set_charset("utf8");
    
    $sql = "
        SELECT COUNT(*) as total_entradas
        FROM historico_acesso
        WHERE tipo_acesso = 'entrada'
        AND DATE(data_hora) = CURDATE()
    ";
    
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        $total = (int)$row['total_entradas'];
        
        echo "SUCCESS|" . $total . "|" . date('Y-m-d');
    } else {
        echo "ERROR|Erro na query";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>