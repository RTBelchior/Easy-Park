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

// --- LÓGICA PARA ADICIONAR VIATURA ---
$msg_erro = "";
$msg_sucesso = "";

// ADICIONAR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_add_carro'])) {
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);
    $matricula = strtoupper(trim($_POST['matricula']));

    if (!empty($marca) && !empty($modelo) && !empty($matricula)) {
        // 1. Inserir na tabela 'carros'
        $sqlInsertCarro = "INSERT INTO carros (Marca, Modelo, Matricula) VALUES (?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsertCarro);
        
        if ($stmtInsert) {
            $stmtInsert->bind_param("sss", $marca, $modelo, $matricula);
            
            if ($stmtInsert->execute()) {
                $novoIdCarro = $conn->insert_id; 
                
                // 2. Ligar o carro ao utilizador
                $sqlLink = "INSERT INTO carros_utilizador (id_carros, id_utilizador) VALUES (?, ?)";
                $stmtLink = $conn->prepare($sqlLink);
                $stmtLink->bind_param("ii", $novoIdCarro, $userId);
                
                if ($stmtLink->execute()) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                } else {
                    $msg_erro = "Erro ao associar viatura.";
                }
            } else {
                $msg_erro = "Erro ao registar (Matrícula pode já existir).";
            }
        }
    } else {
        $msg_erro = "Preencha todos os campos.";
    }
}

// REMOVER
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_remove_carro'])) {
    $idCarroRemover = $_POST['id_carro_remove'];

    // 1. Apagar da tabela de ligação (Garante que só apaga se pertencer ao user logado)
    $sqlDeleteLink = "DELETE FROM carros_utilizador WHERE id_carros = ? AND id_utilizador = ?";
    $stmtDelLink = $conn->prepare($sqlDeleteLink);
    $stmtDelLink->bind_param("ii", $idCarroRemover, $userId);

    if ($stmtDelLink->execute()) {
        // 2. Opcional: Apagar o carro da tabela 'carros' para limpar a BD
        // (Só faz sentido se o carro não for partilhado por outros users)
        $sqlDeleteCar = "DELETE FROM carros WHERE id_carros = ?";
        $stmtDelCar = $conn->prepare($sqlDeleteCar);
        $stmtDelCar->bind_param("i", $idCarroRemover);
        $stmtDelCar->execute();

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $msg_erro = "Erro ao remover viatura.";
    }
}
// -------------------------------------

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

// 2. CARROS DO UTILIZADOR (Adicionei c.id_carros ao Select)
$sqlCarros = "
    SELECT c.id_carros, c.Marca, c.Modelo, c.Matricula
    FROM carros c
    INNER JOIN carros_utilizador cu ON c.id_carros = cu.id_carros
    WHERE cu.id_utilizador = ?
";
$stmtCars = $conn->prepare($sqlCarros);
$stmtCars->bind_param("i", $userId);
$stmtCars->execute();
$carros = $stmtCars->get_result();

// 3. CARTÕES
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

// 4. HISTÓRICO
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

        /* Flex header para o cartão de carros */
        .card-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .card-header-flex h2 {
            margin: 0;
            border: none;
            padding: 0;
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
            vertical-align: middle;
        }

        table th {
            background: #1e3a8a;
            color: white;
            font-weight: 600;
        }
        
        table tr:last-child td { border-bottom: none; }

        /* Botão Adicionar */
        .btn-add {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-add:hover { background-color: #1d4ed8; }

        /* Botão Remover (Lixo) */
        .btn-remove {
            background-color: #fee2e2;
            color: #991b1b;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-remove:hover {
            background-color: #fecaca;
            transform: scale(1.05);
        }

        /* Formulário de Adicionar Carro */
        .car-form-container {
            display: none;
            background-color: #f8fafc;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .form-row {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-submit-car {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 25px;
            font-size: 15px;
        }
        .btn-submit-car:hover { background-color: #15803d; }

        .matricula {
            font-family: 'Consolas', monospace;
            background: #cbd5e1;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #94a3b8;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-info { background-color: #e0f2fe; color: #075985; }

        .alert-error {
            background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 600px) {
            table { display: block; overflow-x: auto; }
            .form-row { flex-direction: column; gap: 15px; }
            .form-group input { margin-bottom: 5px; }
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
        <div class="card-header-flex">
            <h2>🚘 Os Meus Veículos</h2>
            <button class="btn-add" onclick="toggleCarForm()">+ Adicionar Veículos</button>
        </div>

        <?php if(!empty($msg_erro)): ?>
            <div class="alert-error"><?= $msg_erro ?></div>
        <?php endif; ?>

        <!-- Formulário (Escondido) -->
        <div class="car-form-container" id="form-Veiculos">
            <form method="POST" action="">
                <p style="margin-top:0; font-weight:bold; color:#1e3a8a; font-size:16px; margin-bottom: 20px;">Novo Veículo</p>
                <div class="form-row">
                    <div class="form-group">
                        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Marca</label>
                        <input type="text" name="marca" placeholder="ex: BMW" required>
                    </div>
                    <div class="form-group">
                        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Modelo</label>
                        <input type="text" name="modelo" placeholder="ex: Série 1" required>
                    </div>
                    <div class="form-group">
                        <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Matrícula</label>
                        <input type="text" name="matricula" placeholder="ex: AA-00-BB" style="text-transform:uppercase;" required>
                    </div>
                </div>
                <button type="submit" name="btn_add_carro" class="btn-submit-car">Guardar Veículo</button>
            </form>
        </div>

        <!-- Tabela de Carros -->
        <?php if ($carros->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Matrícula</th>
                        <th style="width: 80px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($car = $carros->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($car['Marca']) ?></td>
                        <td><?= htmlspecialchars($car['Modelo']) ?></td>
                        <td><span class="matricula"><?= htmlspecialchars($car['Matricula']) ?></span></td>
                        <td style="text-align: center;">
                            <!-- Botão de Remover -->
                            <form method="POST" onsubmit="return confirm('Tem a certeza que deseja remover esta viatura?');">
                                <input type="hidden" name="id_carro_remove" value="<?= $car['id_carros'] ?>">
                                <button type="submit" name="btn_remove_carro" class="btn-remove" title="Remover Viatura">
                                    🗑️
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #64748b;">Nenhum Veículo associado a este perfil.</p>
        <?php endif; ?>
    </div>

    <!-- CARTÕES -->
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

<?php include('footer.php'); ?>

<script>
    function toggleCarForm() {
        var form = document.getElementById('form-Veiculos');
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
</script>

</body>
</html>