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
    
    // Buscar informações dos parques
    $sql = "
        SELECT 
            id_parque,
            lotacao_maxima
        FROM parque 
        WHERE id_parque IN (1, 2, 3)
        ORDER BY id_parque
    ";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        die("ERROR|Erro na query: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        $total_max = 0;
        $total_atual = 0;
        $parques = [];
        
        while ($row = $result->fetch_assoc()) {
            $id = (int)$row['id_parque'];
            $max = (int)$row['lotacao_maxima'];
            
            // Calcular lotação atual dinamicamente
            // Conta entradas menos saídas para obter quantos carros estão atualmente no parque
            $sql_lotacao = "
                SELECT 
                    SUM(CASE WHEN tipo_acesso = 'entrada' THEN 1 ELSE 0 END) as entradas,
                    SUM(CASE WHEN tipo_acesso = 'saida' THEN 1 ELSE 0 END) as saidas
                FROM acesso
                WHERE id_parque = " . $id;
            
            $result_lotacao = $conn->query($sql_lotacao);
            
            if (!$result_lotacao) {
                die("ERROR|Erro ao calcular lotação: " . $conn->error);
            }
            
            $lotacao = $result_lotacao->fetch_assoc();
            
            $entradas = (int)($lotacao['entradas'] ?? 0);
            $saidas = (int)($lotacao['saidas'] ?? 0);
            $atual = $entradas - $saidas;
            
            // Garantir que não fique negativo
            if ($atual < 0) {
                $atual = 0;
            }
            
            // Garantir que não ultrapasse a lotação máxima
            if ($atual > $max) {
                $atual = $max;
            }
            
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