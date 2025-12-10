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

    // Buscar todos os formulários com informações do utilizador
    $sql = "
        SELECT 
            f.id_form,
            f.avaliacao_form,
            f.mensagem_form,
            f.data_hora_form,
            u.nome_utilizador,
            u.numero_utilizador
        FROM formulario f
        INNER JOIN utilizadores u ON f.id_utilizador = u.id_utilizador
        ORDER BY f.data_hora_form DESC
    ";
    
    $result = $conn->query($sql);

    if ($result) {
        $formularios = [];
        
        while ($row = $result->fetch_assoc()) {
            $formularios[] = $row;
        }
        
        // Retorna em formato JSON mantendo a estrutura original
        echo "SUCCESS|" . json_encode($formularios, JSON_UNESCAPED_UNICODE);
        
    } else {
        echo "ERROR|Erro na consulta: " . $conn->error;
    }

    $conn->close();

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>