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
        SELECT 
            id_parque,
            lotacao_maxima, 
            lotacao_atual,
            ultima_atualizacao
        FROM parque 
        WHERE id_parque IN (1, 2, 3)
        ORDER BY id_parque
    ";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $total_max = 0;
        $total_atual = 0;
        $parques = [];
        
        while ($row = $result->fetch_assoc()) {
            $id = (int)$row['id_parque'];
            $max = (int)$row['lotacao_maxima'];
            $atual = (int)$row['lotacao_atual'];
            
            $parques[] = $id . "|" . $max . "|" . $atual;
            
            $total_max += $max;
            $total_atual += $atual;
        }
        
        echo "SUCCESS|" . $total_max . "|" . $total_atual . "|" . implode(";", $parques);
    } else {
        echo "ERROR|Nenhum parque encontrado";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>