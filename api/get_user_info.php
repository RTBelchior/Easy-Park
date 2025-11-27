<?php
session_start();
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Não autenticado");
    }
    
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    
    if ($conn->connect_error) {
        die("ERROR|Falha na conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
    $id_utilizador = (int)$_SESSION['id_utilizador'];
    
    // JOIN com tipo_utilizador para obter o tipo
    $sql = "
        SELECT 
            u.nome_utilizador,
            u.email_utilizador,
            tu.tipo_utilizador
        FROM utilizadores u
        INNER JOIN tipo_utilizador tu ON u.id_tipo_utilizador = tu.id_tipo_utilizador
        WHERE u.id_utilizador = " . $id_utilizador;
    
    $result = $conn->query($sql);
    
    if (!$result) {
        die("ERROR|Erro na query: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        $nome = $user['nome_utilizador'];
        $email = $user['email_utilizador'] ?? 'sem-email@easypark.pt';
        $tipo = $user['tipo_utilizador'];
        
        // Criar iniciais
        $palavras = explode(' ', $nome);
        if (count($palavras) >= 2) {
            $iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[count($palavras) - 1], 0, 1));
        } else {
            $iniciais = strtoupper(substr($nome, 0, 2));
        }
        
        echo "SUCCESS|" . $nome . "|" . $tipo . "|" . $iniciais . "|" . $email;
    } else {
        echo "ERROR|Utilizador não encontrado";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>