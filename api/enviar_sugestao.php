<?php
header('Content-Type: text/plain; charset=utf-8');
session_start();

// Configurações da Base de Dados
$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    // 1. Verificar Autenticação
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Tem de iniciar sessão para enviar sugestões.");
    }
    
    // 2. Verificar Método
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("ERROR|Método inválido.");
    }

    $id_utilizador = $_SESSION['id_utilizador'];

    // 3. Receber e Limpar Dados
    $avaliacao = isset($_POST['avaliacao']) ? intval($_POST['avaliacao']) : 0;
    $sugestao = isset($_POST['sugestao']) ? trim($_POST['sugestao']) : '';
    $data_hora = date('Y-m-d H:i:s');

    // 4. Validações
    if ($avaliacao < 1 || $avaliacao > 5) {
        die("ERROR|Por favor, selecione uma classificação de 1 a 5 estrelas.");
    }

    if (empty($sugestao)) {
        die("ERROR|Por favor, escreva a sua sugestão ou comentário.");
    }

    // 5. Conexão DB
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        die("ERROR|Erro de conexão à base de dados.");
    }
    $conn->set_charset("utf8mb4");

    // 6. Inserir na tabela
    $sql = "INSERT INTO formulario (avaliacao_form, mensagem_form, data_hora_form, id_utilizador) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // 's' para avaliacao (enum é string na bind), 's' sugestao, 's' data, 'i' id_user
        // Nota: Se avaliacao na BD for ENUM('1','2'...), passamos como string
        $valStr = (string)$avaliacao;
        $stmt->bind_param("sssi", $valStr, $sugestao, $data_hora, $id_utilizador);
        
        if ($stmt->execute()) {
            echo "SUCCESS|Obrigado! A sua sugestão foi enviada com sucesso.";
        } else {
            echo "ERROR|Erro ao gravar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "ERROR|Erro na preparação da consulta.";
    }

    $conn->close();

} catch (Exception $e) {
    echo "ERROR|Erro de sistema: " . $e->getMessage();
}
?>