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
    
    // Parâmetros de paginação
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    $offset = ($page - 1) * $limit;
    
    // Parâmetros de filtro
    $nome = isset($_GET['nome']) ? $_GET['nome'] : '';
    $numero = isset($_GET['numero']) ? $_GET['numero'] : '';
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
    $parque = isset($_GET['parque']) ? $_GET['parque'] : '';
    $dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
    $dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
    
    // Construir WHERE clause
    $where = [];
    $params = [];
    $types = '';
    
    if (!empty($nome)) {
        $where[] = "u.nome LIKE ?";
        $params[] = "%$nome%";
        $types .= 's';
    }
    
    if (!empty($numero)) {
        $where[] = "u.numero = ?";
        $params[] = $numero;
        $types .= 'i';
    }
    
    if (!empty($tipo)) {
        $where[] = "ha.tipo_acesso = ?";
        $params[] = $tipo;
        $types .= 's';
    }
    
    if (!empty($parque)) {
        $where[] = "ha.id_parque = ?";
        $params[] = $parque;
        $types .= 'i';
    }
    
    if (!empty($dataInicio)) {
        $where[] = "DATE(ha.data_hora) >= ?";
        $params[] = $dataInicio;
        $types .= 's';
    }
    
    if (!empty($dataFim)) {
        $where[] = "DATE(ha.data_hora) <= ?";
        $params[] = $dataFim;
        $types .= 's';
    }
    
    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    
    // Contar total de registros
    $sqlCount = "
        SELECT COUNT(*) as total
        FROM historico_acesso ha
        INNER JOIN cartoes c ON ha.id_cartao = c.id_cartao
        INNER JOIN utilizadores u ON c.id_utilizador = u.id_utilizador
        $whereClause
    ";
    
    if (!empty($params)) {
        $stmtCount = $conn->prepare($sqlCount);
        $stmtCount->bind_param($types, ...$params);
        $stmtCount->execute();
        $resultCount = $stmtCount->get_result();
        $totalRecords = $resultCount->fetch_assoc()['total'];
        $stmtCount->close();
    } else {
        $resultCount = $conn->query($sqlCount);
        $totalRecords = $resultCount->fetch_assoc()['total'];
    }
    
    // Buscar registros com paginação
    $sql = "
        SELECT 
            ha.data_hora,
            u.nome,
            u.numero,
            ha.tipo_acesso,
            ha.id_parque,
            c.numero_cartao
        FROM historico_acesso ha
        INNER JOIN cartoes c ON ha.id_cartao = c.id_cartao
        INNER JOIN utilizadores u ON c.id_utilizador = u.id_utilizador
        $whereClause
        ORDER BY ha.data_hora DESC
        LIMIT ? OFFSET ?
    ";
    
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $records = [];
        
        while ($row = $result->fetch_assoc()) {
            // Formato: data_hora,nome,numero,tipo,parque,cartao
            $records[] = 
                $row['data_hora'] . ',' .
                $row['nome'] . ',' .
                $row['numero'] . ',' .
                $row['tipo_acesso'] . ',' .
                $row['id_parque'] . ',' .
                $row['numero_cartao'];
        }
        
        echo "SUCCESS|" . $totalRecords . "|" . implode(';', $records);
    } else {
        echo "SUCCESS|0|EMPTY";
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>