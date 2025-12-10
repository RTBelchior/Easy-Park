<?php
header('Content-Type: text/plain; charset=utf-8');
session_start();

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Não autorizado");
    }
    
    // Verifica se foi enviado um ID via POST
    if (!isset($_POST['id_veiculo'])) {
        die("ERROR|ID não fornecido");
    }

    $userId = $_SESSION['id_utilizador'];
    $veiculoId = intval($_POST['id_veiculo']);

    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        die("ERROR|Falha na conexão");
    }

    // 1. Apagar da tabela de ligação
    $sqlLink = "DELETE FROM veiculos_utilizador WHERE id_veiculos = ? AND id_utilizador = ?";
    $stmt = $conn->prepare($sqlLink);
    $stmt->bind_param("ii", $veiculoId, $userId);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // 2. Apagar o veículo em si
        $sqlCar = "DELETE FROM veiculos WHERE id_veiculos = ?";
        $stmtCar = $conn->prepare($sqlCar);
        $stmtCar->bind_param("i", $veiculoId);
        $stmtCar->execute();
        
        echo "SUCCESS|Veículo removido";
    } else {
        echo "ERROR|Erro ao remover (ou veículo não encontrado)";
    }

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>