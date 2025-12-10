<?php
session_start();
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

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
        die("ERROR|Falha na conexão");
    }
    $conn->set_charset("utf8mb4");
    
    $id_utilizador = (int)$_SESSION['id_utilizador'];
    
    // ADICIONEI: u.ativo_utilizador
    $sql = "
        SELECT 
            u.nome_utilizador,
            u.email_utilizador,
            u.ativo_utilizador,
            tu.tipo_utilizador
        FROM utilizadores u
        INNER JOIN tipo_utilizador tu ON u.id_tipo_utilizador = tu.id_tipo_utilizador
        WHERE u.id_utilizador = " . $id_utilizador;
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        $nome = $user['nome_utilizador'];
        $email = $user['email_utilizador'] ?? 'sem-email@easypark.pt';
        $tipo = $user['tipo_utilizador'];
        $ativo = $user['ativo_utilizador']; // Novo campo
        
        // Criar iniciais
        $palavras = explode(' ', $nome);
        if (count($palavras) >= 2) {
            $iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[count($palavras) - 1], 0, 1));
        } else {
            $iniciais = strtoupper(substr($nome, 0, 2));
        }
        
        // Output: SUCCESS|nome|tipo|iniciais|email|ativo
        echo "SUCCESS|" . $nome . "|" . $tipo . "|" . $iniciais . "|" . $email . "|" . $ativo;
    } else {
        echo "ERROR|Utilizador não encontrado";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>