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
    
    $conn->set_charset("utf8");
    
    $id_utilizador = $_SESSION['id_utilizador'];
    
    $sql = "
        SELECT 
            nome,
            tipo,
            email
        FROM utilizadores
        WHERE id_utilizador = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utilizador);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Criar iniciais
        $palavras = explode(' ', $user['nome']);
        if (count($palavras) >= 2) {
            $iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[count($palavras) - 1], 0, 1));
        } else {
            $iniciais = strtoupper(substr($user['nome'], 0, 2));
        }
        
        // Traduzir tipo
        $tipos = [
            'administrador' => 'Administrador',
            'funcionario' => 'Funcionário',
            'cliente' => 'Cliente'
        ];
        $tipo_formatado = $tipos[$user['tipo']] ?? $user['tipo'];
        
        echo "SUCCESS|" . $user['nome'] . "|" . $tipo_formatado . "|" . $iniciais . "|" . $user['email'];
    } else {
        echo "ERROR|Utilizador não encontrado";
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>