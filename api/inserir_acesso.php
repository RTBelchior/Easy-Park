<?php
header("Content-Type: text/plain; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "easypark");
if ($conn->connect_error) {
    die("ERRO|Falha_na_BD");
}

$numero_cartao = $_POST['numero_cartao'] ?? $_GET['numero_cartao'] ?? null;
$id_parque     = $_POST['id_parque']     ?? $_GET['id_parque']     ?? 1;

if (!$numero_cartao) {
    die("ERRO|cartao_nao_enviado");
}

/* 1) Verificar se o cartão existe */
$sql = "
    SELECT c.id_cartao, u.nome_utilizador 
    FROM cartoes c
    INNER JOIN utilizadores u ON c.id_utilizador = u.id_utilizador
    WHERE c.numero_cartao = ? AND c.ativo_cartao = 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $numero_cartao);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("ERRO|cartao_invalido");
}

$row = $res->fetch_assoc();
$id_cartao = $row['id_cartao'];
$nome = explode(" ", $row['nome_utilizador'])[0];

/* 2) Verificar permissão de acesso ao parque */
$sql = "SELECT 1 FROM cartao_parque WHERE id_cartao = ? AND id_parque = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_cartao, $id_parque);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("ERRO|sem_acesso");
}

/* 3) Lotação atual */
$sql = "
    SELECT 
    SUM(CASE WHEN tipo_acesso='entrada' THEN 1 ELSE 0 END) AS entradas,
    SUM(CASE WHEN tipo_acesso='saida' THEN 1 ELSE 0 END) AS saidas
    FROM acesso
    WHERE id_parque = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_parque);
$stmt->execute();
$lot = $stmt->get_result()->fetch_assoc();

$entradas = intval($lot['entradas'] ?? 0);
$saidas   = intval($lot['saidas'] ?? 0);
$atual    = $entradas - $saidas;

/* 4) Lotação máxima */
$sql = "SELECT lotacao_maxima FROM parque WHERE id_parque = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_parque);
$stmt->execute();

$max = $stmt->get_result()->fetch_assoc()['lotacao_maxima'];

/* 5) Último acesso */
$sql = "SELECT tipo_acesso FROM acesso WHERE id_cartao=? ORDER BY data_hora_acesso DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cartao);
$stmt->execute();
$res = $stmt->get_result();

$proximo = "entrada";
if ($res->num_rows > 0) {
    $ultimo = $res->fetch_assoc()['tipo_acesso'];
    if ($ultimo == "entrada") $proximo = "saida";
}

/* 6) Bloquear entrada se estiver cheio */
if ($proximo == "entrada" && $atual >= $max) {
    die("ERRO|parque_cheio");
}

/* 7) Registrar no banco */
$sql = "
    INSERT INTO acesso (tipo_acesso, data_hora_acesso, id_cartao, id_parque)
    VALUES (?, NOW(), ?, ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $proximo, $id_cartao, $id_parque);
$stmt->execute();

/* 8) Resposta final */
echo "OK|" . $proximo . "|" . $nome;

$conn->close();
?>
