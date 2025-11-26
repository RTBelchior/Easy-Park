<?php
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "easypark");

if ($conn->connect_error) {
    die(json_encode(["status" => "erro", "mensagem" => "Falha na ligação à base de dados"]));
}

// RECEBE DADOS
$numero_cartao = $_POST['numero_cartao'] ?? null;
$id_parque = $_POST['id_parque'] ?? 1;

if (!$numero_cartao) {
    echo json_encode(["status" => "erro", "mensagem" => "numero_cartao não enviado"]);
    exit;
}

// VALIDAR CARTÃO
$sql = "SELECT id_cartao FROM cartoes WHERE numero_cartao = ? AND ativo = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $numero_cartao);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status" => "erro", "mensagem" => "Cartão inválido ou inativo"]);
    exit;
}

$row = $result->fetch_assoc();
$id_cartao = $row['id_cartao'];

// BUSCAR ÚLTIMO ACESSO
$sql_last = "SELECT tipo_acesso FROM historico_acesso 
             WHERE id_cartao = ?
             ORDER BY data_hora DESC 
             LIMIT 1";

$stmt_last = $conn->prepare($sql_last);
$stmt_last->bind_param("i", $id_cartao);
$stmt_last->execute();
$res_last = $stmt_last->get_result();

// DEFINIR ENTRADA OU SAÍDA
if ($res_last->num_rows == 0) {
    $tipo_acesso = "entrada";  // primeira vez
} else {
    $ultimo = $res_last->fetch_assoc();
    $tipo_acesso = ($ultimo['tipo_acesso'] === "entrada") ? "saida" : "entrada";
}

// INSERIR NOVO REGISTO
$sql_insert = "INSERT INTO historico_acesso
               (tipo_acesso, data_hora, estado_parque, mensagem, id_cartao, id_parque)
               VALUES (?, NOW(), 0, '', ?, ?)";

$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sii", $tipo_acesso, $id_cartao, $id_parque);
$stmt_insert->execute();

echo json_encode([
    "status" => "sucesso",
    "mensagem" => "Acesso registado",
    "tipo" => $tipo_acesso
]);

?>
