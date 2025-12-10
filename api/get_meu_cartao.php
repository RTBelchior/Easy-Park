<?php
header('Content-Type: text/plain; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuração da ligação
$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    // Verificação de autenticação
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Utilizador não autenticado");
    }

    $id_utilizador = $_SESSION['id_utilizador'];

    // Conexão MySQLi
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        die("ERROR|Falha na conexão: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    // Query para buscar TODOS os cartões e os seus tipos
    $sql = "
        SELECT 
            c.numero_cartao,
            c.ativo_cartao,
            c.data_registo_cartao,
            tc.tipo_cartao
        FROM cartoes c
        INNER JOIN tipo_cartao tc ON c.id_tipo_cartao = tc.id_tipo_cartao
        WHERE c.id_utilizador = ?
        ORDER BY c.data_registo_cartao DESC
    ";

    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar se existem resultados
        if ($result->num_rows > 0) {
            $listaOutput = [];

            while ($row = $result->fetch_assoc()) {
                // Formato linha: NUMERO|ATIVO|DATA|TIPO
                $linha = $row['numero_cartao'] . '|' . 
                         $row['ativo_cartao'] . '|' . 
                         $row['data_registo_cartao'] . '|' . 
                         $row['tipo_cartao'];
                
                $listaOutput[] = $linha;
            }

            // Retorna: SUCCESS | cartao1;cartao2;cartao3...
            echo "SUCCESS|" . implode(';', $listaOutput);

        } else {
            echo "ERROR|Não tem cartões associados.";
        }
        
        $stmt->close();
    } else {
        echo "ERROR|Erro na preparação da query: " . $conn->error;
    }

    $conn->close();

} catch (Exception $e) {
    echo "ERROR|Erro: " . $e->getMessage();
}
?>