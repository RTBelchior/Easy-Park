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
        die("ERROR|Falha na conexão");
    }
    
    $conn->set_charset("utf8");
    
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
            FROM historico_acesso
            WHERE tipo_acesso = 'entrada'
            AND DATE(data_hora) = ?
        ";
        $stmt = $conn->prepare($sql_entradas);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $entradas[] = (int)$row['total'];
        $stmt->close();
        
        // Contar saídas
        $sql_saidas = "
            SELECT COUNT(*) as total
            FROM historico_acesso
            WHERE tipo_acesso = 'saida'
            AND DATE(data_hora) = ?
        ";
        $stmt = $conn->prepare($sql_saidas);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $saidas[] = (int)$row['total'];
        $stmt->close();
    }
    
    echo "SUCCESS|" . $mes . "|" . $ano . "|" . implode(",", $entradas) . "|" . implode(",", $saidas);
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>