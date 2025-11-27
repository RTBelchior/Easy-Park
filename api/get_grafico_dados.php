<?php
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    
    if ($conn->connect_error) {
        die("ERROR|Falha na conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
    $ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
    
    if ($mes < 1 || $mes > 12) $mes = date('n');
    if ($ano < 2020 || $ano > 2030) $ano = date('Y');
    
    $num_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    
    $entradas = [];
    $saidas = [];
    
    for ($dia = 1; $dia <= $num_dias; $dia++) {
        $data = sprintf("%04d-%02d-%02d", $ano, $mes, $dia);
        
        // Contar entradas
        $sql_entradas = "
            SELECT COUNT(*) as total
            FROM acesso
            WHERE tipo_acesso = 'entrada'
            AND DATE(data_hora_acesso) = '" . $conn->real_escape_string($data) . "'
        ";
        
        $result = $conn->query($sql_entradas);
        
        if (!$result) {
            die("ERROR|Erro ao contar entradas: " . $conn->error);
        }
        
        $row = $result->fetch_assoc();
        $entradas[] = (int)$row['total'];
        
        // Contar saídas
        $sql_saidas = "
            SELECT COUNT(*) as total
            FROM acesso
            WHERE tipo_acesso = 'saida'
            AND DATE(data_hora_acesso) = '" . $conn->real_escape_string($data) . "'
        ";
        
        $result = $conn->query($sql_saidas);
        
        if (!$result) {
            die("ERROR|Erro ao contar saídas: " . $conn->error);
        }
        
        $row = $result->fetch_assoc();
        $saidas[] = (int)$row['total'];
    }
    
    echo "SUCCESS|" . $mes . "|" . $ano . "|" . implode(",", $entradas) . "|" . implode(",", $saidas);
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>