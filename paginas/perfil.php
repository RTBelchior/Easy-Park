<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['id_utilizador'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['id_utilizador'];

// Configuração da Base de Dados
$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

$conn = new mysqli($host, $utilizador, $senha, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// 1. DADOS DO UTILIZADOR
$sqlUser = "
    SELECT u.id_utilizador, u.nome_utilizador, u.email_utilizador, u.ativo_utilizador, t.tipo_utilizador
    FROM utilizadores u
    INNER JOIN tipo_utilizador t ON u.id_tipo_utilizador = t.id_tipo_utilizador
    WHERE u.id_utilizador = ?
";
$stmt = $conn->prepare($sqlUser);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("<h3 style='color:red; text-align:center; margin-top:50px;'>❌ Utilizador não encontrado.</h3>");
}

// 2. [NOVO] CARROS DO UTILIZADOR
// Liga a tabela carros -> carros_utilizador -> utilizador
$sqlCarros = "
    SELECT c.Marca, c.Modelo, c.Matricula
    FROM carros c
    INNER JOIN carros_utilizador cu ON c.id_carros = cu.id_carros
    WHERE cu.id_utilizador = ?
";
$stmtCars = $conn->prepare($sqlCarros);
$stmtCars->bind_param("i", $userId);
$stmtCars->execute();
$carros = $stmtCars->get_result();

// 3. [ATUALIZADO] CARTÕES + TIPO DE CARTÃO
// Adicionado JOIN com tipo_cartao
$sqlCartoes = "
    SELECT c.id_cartao, c.numero_cartao, c.ativo_cartao, c.data_registo_cartao, t.tipo_cartao
    FROM cartoes c
    INNER JOIN tipo_cartao t ON c.id_tipo_cartao = t.id_tipo_cartao
    WHERE c.id_utilizador = ?
";
$stmt2 = $conn->prepare($sqlCartoes);
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$cartoes = $stmt2->get_result();

// 4. HISTÓRICO DE ACESSOS
$sqlHistorico = "
    SELECT a.tipo_acesso, a.data_hora_acesso,
           p.id_parque, c.numero_cartao, univ.nome_universidade
    FROM acesso a
    INNER JOIN cartoes c ON a.id_cartao = c.id_cartao
    INNER JOIN parque p ON a.id_parque = p.id_parque
    INNER JOIN universidade univ ON p.id_universidade = univ.id_universidade
    WHERE c.id_utilizador = ?
    ORDER BY a.data_hora_acesso DESC
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
            color: #333;
        }

        .container {
            max-width: 1000px;
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
            animation: fadeIn 0.8s ease;
        }

        h2 {
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
            font-size: 15px;
        }

        table th {
            background: #1e3a8a;
            color: white;
            font-weight: 600;
        }
        
        table tr:last-child td { border-bottom: none; }

        /* Badges e Estilos Extra */
        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #e0f2fe; color: #075985; }

        .matricula {
            font-family: 'Consolas', monospace;
            background: #cbd5e1;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #94a3b8;
            font-weight: bold;
            letter-spacing: 1px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 600px) {
            table { display: block; overflow-x: auto; }
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container">
    

    <!-- PERFIL -->
    <div class="card">
        <h2>👤 Informações Pessoais</h2>
        <p><strong>Nome:</strong> <?= htmlspecialchars($user['nome_utilizador']) ?></p>
        <p><strong>Perfil:</strong> <?= htmlspecialchars($user['tipo_utilizador']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email_utilizador']) ?></p>
        <p><strong>Estado da Conta:</strong> 
            <?= $user['ativo_utilizador'] ? '<span class="badge badge-success">Ativa</span>' : '<span class="badge badge-danger">Inativa</span>' ?>
        </p>
    </div>

    <!-- CARROS -->
    <div class="card">
        <h2>🚘 As Minhas Viaturas</h2>
        <?php if ($carros->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Matrícula</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($car = $carros->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($car['Marca']) ?></td>
                        <td><?= htmlspecialchars($car['Modelo']) ?></td>
                        <td><span class="matricula"><?= htmlspecialchars($car['Matricula']) ?></span></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #64748b;">Nenhuma viatura associada a este perfil.</p>
        <?php endif; ?>
    </div>

    <!-- CARTÕES (ATUALIZADO COM TIPO) -->
    <div class="card">
        <h2>💳 Os Meus Cartões</h2>
        <?php if ($cartoes->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Número / ID</th>
                        <th>Estado</th>
                        <th>Data Registo</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($c = $cartoes->fetch_assoc()): ?>
                    <tr>
                        <!-- Exibe Tag ou Cartão -->
                        <td><span class="badge badge-info"><?= ucfirst(htmlspecialchars($c['tipo_cartao'])) ?></span></td>
                        <td><?= htmlspecialchars($c['numero_cartao']) ?></td>
                        <td>
                            <?= $c['ativo_cartao'] ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-danger">Inativo</span>' ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($c['data_registo_cartao'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #64748b;">Não tem cartões associados.</p>
        <?php endif; ?>
    </div>

    <!-- HISTÓRICO -->
    <div class="card">
        <h2>🕒 Histórico de Acessos</h2>
        <?php if ($historico->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ação</th>
                        <th>Data e Hora</th>
                        <th>Universidade</th>
                        <th>Cartão</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($h = $historico->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if($h['tipo_acesso'] == 'entrada'): ?>
                                <span style="color:green; font-weight:bold;">Entrada</span>
                            <?php else: ?>
                                <span style="color:red; font-weight:bold;">Saída</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($h['data_hora_acesso'])) ?></td>
                        <td><?= htmlspecialchars($h['nome_universidade']) ?></td>
                        <td><?= htmlspecialchars($h['numero_cartao']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #64748b;">Ainda não existe histórico de acessos.</p>
        <?php endif; ?>
    </div>

</div>

<?php  include('footer.php'); ?>

</body>
</html>