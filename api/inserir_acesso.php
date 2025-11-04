<?php
header("Content-Type: application/json; charset=UTF-8");

// Ligação à base de dados
$conn = new mysqli("localhost", "root", "", "easypark");

if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Falha na ligação à base de dados"]));
}

// Dados recebidos do ESP32
$numero_cartao = $_POST['numero_cartao'] ?? null;
$tipo_acesso   = $_POST['tipo_acesso'] ?? null;
$id_parque     = $_POST['id_parque'] ?? 1;

if (!$numero_cartao || !$tipo_acesso) {
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos"]);
    exit;
}

// Verifica se o cartão existe e está ativo
$sql = "SELECT id_cartao FROM cartoes WHERE numero_cartao = ? AND ativo = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $numero_cartao);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_cartao = $row['id_cartao'];

    // Regista o acesso na tabela historico_acesso
    $sql_insert = "INSERT INTO historico_acesso (tipo_acesso, data_hora, estado_parque, mensagem, id_cartao, id_parque)
                   VALUES (?, NOW(), 0, '', ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sii", $tipo_acesso, $id_cartao, $id_parque);
    $stmt_insert->execute();

    echo json_encode(["status" => "sucesso", "mensagem" => "Acesso registado com sucesso"]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Cartão inválido ou inativo"]);
}

$conn->close();
?>
