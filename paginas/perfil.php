<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id_utilizador'];

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

$conn = new mysqli($host, $utilizador, $senha, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$sqlUser = "
    SELECT id_utilizador, nome, tipo, email, ativo
    FROM utilizadores
    WHERE id_utilizador = ?
";

$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("<h3 style='color:red'>❌ Utilizador não encontrado.</h3>");
}

$sqlCartoes = "
    SELECT id_cartao, numero_cartao, ativo, data_registo
    FROM cartoes
    WHERE id_utilizador = ?
";

$stmt2 = $conn->prepare($sqlCartoes);
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$cartoes = $stmt2->get_result();

$sqlHistorico = "
    SELECT h.tipo_acesso, h.data_hora, h.estado_parque,
           p.id_parque, c.numero_cartao
    FROM historico_acesso h
    INNER JOIN cartoes c ON h.id_cartao = c.id_cartao
    INNER JOIN parque p ON h.id_parque = p.id_parque
    WHERE c.id_utilizador = ?
    ORDER BY h.data_hora DESC
";

$stmt3 = $conn->prepare($sqlHistorico);
$stmt3->bind_param("i", $userId);
$stmt3->execute();
$historico = $stmt3->get_result();

?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - EasyPark</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            min-height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: fadeIn 1s ease;
        }

        h2 {
            color: #1e3a8a;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }

        table th {
            background: #e0e7ff;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container">

    <div class="card">
        <h2>Informações do Utilizador</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($user['nome']) ?></p>
        <p><strong>Tipo:</strong> <?= htmlspecialchars($user['tipo']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Ativo:</strong> <?= $user['ativo'] ? 'Sim' : 'Não' ?></p>
    </div>

    <div class="card">
        <h2>Cartões Associados</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Ativo</th>
                <th>Data de Registo</th>
            </tr>

            <?php while($c = $cartoes->fetch_assoc()): ?>
                <tr>
                    <td><?= $c['id_cartao'] ?></td>
                    <td><?= $c['numero_cartao'] ?></td>
                    <td><?= $c['ativo'] ? 'Sim' : 'Não' ?></td>
                    <td><?= $c['data_registo'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- HISTÓRICO -->
    <div class="card">
        <h2>Histórico de Entradas e Saídas</h2>
        <table>
            <tr>
                <th>Tipo</th>
                <th>Data/Hora</th>
                <th>Parque</th>
                <th>Cartão</th>
                <th>Estado</th>
            </tr>

            <?php while($h = $historico->fetch_assoc()): ?>
                <tr>
                    <td><?= ucfirst($h['tipo_acesso']) ?></td>
                    <td><?= $h['data_hora'] ?></td>
                    <td>Parque <?= $h['id_parque'] ?></td>
                    <td><?= $h['numero_cartao'] ?></td>
                    <td><?= $h['estado_parque'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

<?php include('footer.php'); ?>

</body>
</html>
