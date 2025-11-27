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
    
    // Parâmetros de paginação
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;
    
    // Parâmetros de filtro
    $nome = isset($_GET['nome']) ? trim($_GET['nome']) : '';
    $numero = isset($_GET['numero']) ? trim($_GET['numero']) : '';
    $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
    $parque = isset($_GET['parque']) ? trim($_GET['parque']) : '';
    $dataInicio = isset($_GET['data_inicio']) ? trim($_GET['data_inicio']) : '';
    $dataFim = isset($_GET['data_fim']) ? trim($_GET['data_fim']) : '';
    
    
    // Construir query base
    $sqlBase = "
        FROM acesso a
        INNER JOIN cartoes c ON a.id_cartao = c.id_cartao
        INNER JOIN utilizadores u ON c.id_utilizador = u.id_utilizador
    ";
    
    // Construir WHERE clause
    $where = [];
    
    if (!empty($nome)) {
        $where[] = "u.nome_utilizador LIKE '%" . $conn->real_escape_string($nome) . "%'";
    }
    
    if (!empty($numero)) {
        $where[] = "u.numero_utilizador = " . (int)$numero;
    }
    
    if (!empty($tipo)) {
        $where[] = "a.tipo_acesso = '" . $conn->real_escape_string($tipo) . "'";
    }
    
    if (!empty($parque)) {
        $where[] = "a.id_parque = " . (int)$parque;
    }
    
    if (!empty($dataInicio)) {
        $where[] = "DATE(a.data_hora_acesso) >= '" . $conn->real_escape_string($dataInicio) . "'";
    }
    
    if (!empty($dataFim)) {
        $where[] = "DATE(a.data_hora_acesso) <= '" . $conn->real_escape_string($dataFim) . "'";
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Contar total de registros
    $sqlCount = "SELECT COUNT(*) as total " . $sqlBase . " " . $whereClause;
    $resultCount = $conn->query($sqlCount);
    
    if (!$resultCount) {
        die("ERROR|Erro na contagem: " . $conn->error);
    }
    
    $totalRecords = $resultCount->fetch_assoc()['total'];
    
    // Buscar registros com paginação
    $sql = "
        SELECT 
            a.data_hora_acesso,
            u.nome_utilizador,
            u.numero_utilizador,
            a.tipo_acesso,
            a.id_parque,
            c.numero_cartao
        " . $sqlBase . "
        " . $whereClause . "
        ORDER BY a.data_hora_acesso DESC
        LIMIT " . $limit . " OFFSET " . $offset;
    
    $result = $conn->query($sql);
    
    if (!$result) {
        die("ERROR|Erro na query: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        $records = [];
        
        while ($row = $result->fetch_assoc()) {
            // Escapar vírgulas no nome para não quebrar o formato
            $nome = str_replace(',', ';', $row['nome_utilizador']);
            
            // Formato: data_hora,nome,numero,tipo,parque,cartao
            $records[] = 
                $row['data_hora_acesso'] . ',' .
                $nome . ',' .
                $row['numero_utilizador'] . ',' .
                $row['tipo_acesso'] . ',' .
                $row['id_parque'] . ',' .
                $row['numero_cartao'];
        }
        
        echo "SUCCESS|" . $totalRecords . "|" . implode(';', $records);
    } else {
        echo "SUCCESS|0|EMPTY";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>