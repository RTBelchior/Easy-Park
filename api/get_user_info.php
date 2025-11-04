<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    // Verificar se o utilizador está logado
    if (!isset($_SESSION['id_utilizador'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'Utilizador não autenticado'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    
    $id_utilizador = $_SESSION['id_utilizador'];
    
    // Buscar dados do utilizador
    $sql = "
        SELECT 
            id_utilizador,
            nome,
            numero,
            tipo,
            email,
            ativo
        FROM utilizadores
        WHERE id_utilizador = ?
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_utilizador);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Traduzir tipo de utilizador
        $tipos = [
            'aluno' => 'Aluno',
            'professor' => 'Professor',
            'administrador' => 'Administrador',
            'funcionario' => 'Funcionário'
        ];
        
        $user['tipo_formatado'] = $tipos[$user['tipo']] ?? $user['tipo'];
        
        // Criar iniciais do nome
        $palavras = explode(' ', $user['nome']);
        $iniciais = '';
        
        if (count($palavras) >= 2) {
            // Primeira letra do primeiro e último nome
            $iniciais = strtoupper(substr($palavras[0], 0, 1) . substr($palavras[count($palavras) - 1], 0, 1));
        } else {
            // Primeiras duas letras do nome
            $iniciais = strtoupper(substr($user['nome'], 0, 2));
        }
        
        $user['iniciais'] = $iniciais;
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'user' => $user
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Utilizador não encontrado'
        ], JSON_UNESCAPED_UNICODE);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao buscar dados do utilizador',
        'error_details' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>