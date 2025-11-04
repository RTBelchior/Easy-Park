<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 0);
error_reporting(0);

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    
    $mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
    $ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
    
    if ($mes < 1 || $mes > 12) $mes = date('n');
    if ($ano < 2020 || $ano > 2030) $ano = date('Y');
    
    $num_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
    
    $dias = [];
    $entradas = [];
    $saidas = [];
    
    for ($dia = 1; $dia <= $num_dias; $dia++) {
        $data = sprintf("%04d-%02d-%02d", $ano, $mes, $dia);
        $dias[] = $dia;
        
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
    
    if (ob_get_length()) ob_clean();
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'mes' => $mes,
        'ano' => $ano,
        'dias' => $dias,
        'entradas' => $entradas,
        'saidas' => $saidas
    ], JSON_UNESCAPED_UNICODE);
    
    $conn->close();
    
} catch (Exception $e) {
    if (ob_get_length()) ob_clean();
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar dados',
        'error_details' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

exit;
?>