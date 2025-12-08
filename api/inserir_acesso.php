<?php
header("Content-Type: text/plain; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "easypark");
if ($conn->connect_error) {
    die("ERRO|Falha na BD");
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

// ← AQUI ESTÁ A CORREÇÃO IMPORTANTE
$numero_cartao = $_POST['numero_cartao'] ?? $_GET['numero_cartao'] ?? null;
$id_parque     = $_POST['id_parque']     ?? $_GET['id_parque']     ?? 1;

if (!$numero_cartao) {
    die("ERRO|Cartao nao enviado");
}


/* 1) Verificar se o cartão existe e está ativo */
$sql = "SELECT id_cartao FROM cartoes WHERE numero_cartao = ? AND ativo_cartao = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $numero_cartao);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("NEGADO|Cartao inativo");
}

$row = $res->fetch_assoc();
$id_cartao = $row['id_cartao'];

/* 2) Verificar se tem permissão para este parque */
$sql = "SELECT 1 FROM cartao_parque WHERE id_cartao = ? AND id_parque = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_cartao, $id_parque);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("NEGADO|Sem acesso ao parque");
}

/* 3) Calcular lotação atual dinâmica */
$sqlL = "
    SELECT  
        SUM(CASE WHEN tipo_acesso='entrada' THEN 1 ELSE 0 END) AS entradas,
        SUM(CASE WHEN tipo_acesso='saida' THEN 1 ELSE 0 END) AS saidas
    FROM acesso
    WHERE id_parque = ?
";
$stmt = $conn->prepare($sqlL);
$stmt->bind_param("i", $id_parque);
$stmt->execute();
$lot = $stmt->get_result()->fetch_assoc();

$entradas = intval($lot['entradas'] ?? 0);
$saidas   = intval($lot['saidas'] ?? 0);

$atual = $entradas - $saidas;
if ($atual < 0) $atual = 0;

/* 4) Buscar lotação máxima */
$sqlM = "SELECT lotacao_maxima FROM parque WHERE id_parque = ?";
$stmt = $conn->prepare($sqlM);
$stmt->bind_param("i", $id_parque);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$max = intval($row['lotacao_maxima']);

/* 5) Descobrir último movimento deste cartão */
$sqlU = "SELECT tipo_acesso FROM acesso WHERE id_cartao=? ORDER BY data_hora_acesso DESC LIMIT 1";
$stmt = $conn->prepare($sqlU);
$stmt->bind_param("i", $id_cartao);
$stmt->execute();
$res = $stmt->get_result();

$proximo = "entrada";
if ($res->num_rows > 0) {
    $ultimo = $res->fetch_assoc()['tipo_acesso'];
    if ($ultimo == "entrada") $proximo = "saida";
}

/* 6) Bloquear entrada se o parque estiver cheio */
if ($proximo == "entrada" && $atual >= $max) {
    die("NEGADO|Parque cheio");
}

/* 7) Registrar acesso */
$sqlI = "INSERT INTO acesso (tipo_acesso, data_hora_acesso, id_cartao, id_parque)
         VALUES (?, NOW(), ?, ?)";
$stmt = $conn->prepare($sqlI);
$stmt->bind_param("sii", $proximo, $id_cartao, $id_parque);
$stmt->execute();

/* 8) Resposta final para o ESP32 */
echo "OK|$proximo|$atual|$max";

$conn->close();
?>
