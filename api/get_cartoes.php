<?php
header('Content-Type: text/plain; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    echo "ERROR|Não autorizado";
    exit();
}

// Configuração da ligação
$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    // Conexão MySQLi
    $conn = new mysqli($host, $utilizador, $senha, $dbname);

    if ($conn->connect_error) {
        die("ERROR|Falha na conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");

    // Buscar todos os cartões com informações do utilizador
    $sql = "
        SELECT 
            c.id_cartao,
            c.numero_cartao,
            c.ativo_cartao,
            c.data_registo_cartao,
            c.id_tipo_cartao,
            u.nome_utilizador,
            u.numero_utilizador
        FROM cartoes c
        INNER JOIN utilizadores u ON c.id_utilizador = u.id_utilizador
        ORDER BY u.nome_utilizador ASC
    ";
    
    $result = $conn->query($sql);

    if ($result) {
        $cartoes = [];
        
        while ($row = $result->fetch_assoc()) {
            $cartoes[] = $row;
        }
        
        // Retorna em formato JSON
        echo "SUCCESS|" . json_encode($cartoes, JSON_UNESCAPED_UNICODE);
        
    } else {
        echo "ERROR|Erro na consulta: " . $conn->error;
    }

    $conn->close();

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>