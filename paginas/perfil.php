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

// Variáveis de controlo
$system_error = "";
$msg_erro = "";
$msg_sucesso = "";

$user = null;
$veiculos = null;
$cartoes = null;
$historico = null;
$tipos_veiculo = []; 

try {
    // Tenta conectar
    $conn = @new mysqli($host, $utilizador, $senha, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Não foi possível estabelecer conexão com a base de dados.");
    }

    // --- 0. BUSCAR TIPOS DE VEÍCULO ---
    $sqlTipos = "SELECT id_tipo_veiculo, nome_tipo_veiculo FROM tipo_veiculo";
    $resTipos = $conn->query($sqlTipos);
    if ($resTipos) {
        while ($row = $resTipos->fetch_assoc()) {
            $tipos_veiculo[] = $row;
        }
    }

    // --- LÓGICA PARA ADICIONAR/REMOVER VIATURA ---
    
    // ADICIONAR
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_add_veiculo'])) {
        $marca = trim($_POST['marca']);
        $modelo = trim($_POST['modelo']);
        $idTipo = intval($_POST['id_tipo_veiculo']);
        
        // --- INICIO DA CORREÇÃO DA MATRÍCULA ---
        $matriculaRaw = $_POST['matricula'];
        // 1. Mete em maiúsculas e remove tudo o que NÃO for letra (A-Z) ou número (0-9)
        // Isto resolve o problema de "FS 34 FS" (remove os espaços)
        $matriculaLimpa = preg_replace('/[^A-Z0-9]/', '', strtoupper($matriculaRaw));

        // 2. Valida se tem exatamente 6 caracteres (padrão 2+2+2)
        if (strlen($matriculaLimpa) === 6) {
            
            // 3. Formata para AA-00-AA (insere os traços manualmente)
            $parte1 = substr($matriculaLimpa, 0, 2);
            $parte2 = substr($matriculaLimpa, 2, 2);
            $parte3 = substr($matriculaLimpa, 4, 2);
            $matriculaFinal = "$parte1-$parte2-$parte3";

            if (!empty($marca) && !empty($modelo) && $idTipo > 0) {
                // Inserir na tabela 'veiculos'
                $sqlInsert = "INSERT INTO veiculos (marca_veiculos, modelo_veiculos, matricula_veiculos, id_tipo_veiculo) VALUES (?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($sqlInsert);
                
                if ($stmtInsert) {
                    // Usa a $matriculaFinal formatada
                    $stmtInsert->bind_param("sssi", $marca, $modelo, $matriculaFinal, $idTipo);
                    
                    if ($stmtInsert->execute()) {
                        $novoIdVeiculo = $conn->insert_id; 
                        
                        // Ligar o veículo ao utilizador
                        $sqlLink = "INSERT INTO veiculos_utilizador (id_veiculos, id_utilizador) VALUES (?, ?)";
                        $stmtLink = $conn->prepare($sqlLink);
                        $stmtLink->bind_param("ii", $novoIdVeiculo, $userId);
                        
                        if ($stmtLink->execute()) {
                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit;
                        } else {
                            $msg_erro = "Erro ao associar viatura ao utilizador.";
                        }
                    } else {
                        $msg_erro = "Erro ao registar (Matrícula pode já existir).";
                    }
                }
            } else {
                $msg_erro = "Preencha a marca, modelo e tipo de veículo.";
            }
        } else {
            $msg_erro = "Matrícula inválida. Deve conter 6 letras/números (ex: AA-00-AA).";
        }
        // --- FIM DA CORREÇÃO DA MATRÍCULA ---
    }

    // REMOVER
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn_remove_veiculo'])) {
        $idVeiculoRemover = $_POST['id_veiculo_remove'];

        // 1. Apagar da tabela de ligação
        $sqlDeleteLink = "DELETE FROM veiculos_utilizador WHERE id_veiculos = ? AND id_utilizador = ?";
        $stmtDelLink = $conn->prepare($sqlDeleteLink);
        $stmtDelLink->bind_param("ii", $idVeiculoRemover, $userId);

        if ($stmtDelLink->execute()) {
            // 2. Apagar da tabela de veículos
            $sqlDeleteCar = "DELETE FROM veiculos WHERE id_veiculos = ?";
            $stmtDelCar = $conn->prepare($sqlDeleteCar);
            $stmtDelCar->bind_param("i", $idVeiculoRemover);
            $stmtDelCar->execute();

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $msg_erro = "Erro ao remover viatura.";
        }
    }

    // 1. DADOS DO UTILIZADOR
    $sqlUser = "SELECT u.id_utilizador, u.nome_utilizador, u.email_utilizador, u.ativo_utilizador, t.tipo_utilizador
                FROM utilizadores u
                INNER JOIN tipo_utilizador t ON u.id_tipo_utilizador = t.id_tipo_utilizador
                WHERE u.id_utilizador = ?";
    $stmt = $conn->prepare($sqlUser);
    if(!$stmt) throw new Exception("Erro ao preparar consulta de utilizador.");
    
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        throw new Exception("Utilizador não encontrado.");
    }

    // 2. VEÍCULOS
    $sqlVeiculos = "
        SELECT v.id_veiculos, v.marca_veiculos, v.modelo_veiculos, v.matricula_veiculos, tv.nome_tipo_veiculo
        FROM veiculos v
        INNER JOIN veiculos_utilizador vu ON v.id_veiculos = vu.id_veiculos
        INNER JOIN tipo_veiculo tv ON v.id_tipo_veiculo = tv.id_tipo_veiculo
        WHERE vu.id_utilizador = ?
    ";
    $stmtCars = $conn->prepare($sqlVeiculos);
    $stmtCars->bind_param("i", $userId);
    $stmtCars->execute();
    $veiculos = $stmtCars->get_result();

    // 3. CARTÕES
    $sqlCartoes = "SELECT c.id_cartao, c.numero_cartao, c.ativo_cartao, c.data_registo_cartao, t.tipo_cartao
                   FROM cartoes c
                   INNER JOIN tipo_cartao t ON c.id_tipo_cartao = t.id_tipo_cartao
                   WHERE c.id_utilizador = ?";
    $stmt2 = $conn->prepare($sqlCartoes);
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
    $cartoes = $stmt2->get_result();

    // 4. HISTÓRICO
    $sqlHistorico = "SELECT a.tipo_acesso, a.data_hora_acesso,
                            p.id_parque, c.numero_cartao, univ.nome_universidade
                     FROM acesso a
                     INNER JOIN cartoes c ON a.id_cartao = c.id_cartao
                     INNER JOIN parque p ON a.id_parque = p.id_parque
                     INNER JOIN universidade univ ON p.id_universidade = univ.id_universidade
                     WHERE c.id_utilizador = ?
                     ORDER BY a.data_hora_acesso DESC";
    $stmt3 = $conn->prepare($sqlHistorico);
    $stmt3->bind_param("i", $userId);
    $stmt3->execute();
    $historico = $stmt3->get_result();

} catch (Exception $e) {
    $system_error = $e->getMessage();
}
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

        .card-error {
            text-align: center;
            border-left: 5px solid #dc2626;
        }
        .error-icon {
            font-size: 50px;
            margin-bottom: 15px;
            display: block;
        }

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

        .car-form-container {
            display: none;
            background-color: #f8fafc;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        /* --- ALTERAÇÕES CSS NO FORMULÁRIO --- */
        .form-row {
            display: flex;
            gap: 20px; /* Aumentado o espaço entre campos */
            flex-wrap: wrap;
            align-items: flex-end; /* Alinha os inputs pela base */
        }

        .form-group {
            flex: 1;
            min-width: 180px; /* Garante tamanho mínimo */
            margin-bottom: 15px; /* Espaço inferior se quebrar linha */
        }
        /* ------------------------------------- */

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background-color: white;
            box-sizing: border-box; /* Garante que o padding não aumenta a largura total */
        }

        .btn-submit-car {
            background-color: #16a34a;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
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
        .badge-warning { background-color: #fef3c7; color: #92400e; }

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
            .form-group input, .form-group select { margin-bottom: 5px; }
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container">
    
    <?php if (!empty($system_error)): ?>
        <div class="card card-error">
            <span class="error-icon">⚠️</span>
            <h2 style="justify-content: center; border-bottom: none;">Ops! Algo correu mal.</h2>
            <p style="color: #dc2626; font-weight: bold;"><?= htmlspecialchars($system_error) ?></p>
            <button onclick="window.location.reload();" class="btn-add" style="margin-top: 20px;">
                🔄 Tentar Novamente
            </button>
        </div>
    
    <?php else: ?>

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

        <!-- VEÍCULOS -->
        <div class="card">
            <div class="card-header-flex">
                <h2>🚘 Os Meus Veículos</h2>
                <button class="btn-add" onclick="toggleCarForm()">+ Adicionar Veículo</button>
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
                            <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Tipo</label>
                            <select name="id_tipo_veiculo" required>
                                <option value="" disabled selected>Selecionar...</option>
                                <?php foreach ($tipos_veiculo as $tipo): ?>
                                    <option value="<?= $tipo['id_tipo_veiculo'] ?>">
                                        <?= htmlspecialchars(ucfirst($tipo['nome_tipo_veiculo'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Marca</label>
                            <input type="text" name="marca" placeholder="ex: BMW" required>
                        </div>
                        <div class="form-group">
                            <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Modelo</label>
                            <input type="text" name="modelo" placeholder="ex: Série 1" required>
                        </div>
                        
                        <!-- CAMPO MATRÍCULA ALTERADO -->
                        <div class="form-group">
                            <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Matrícula</label>
                            <input 
                                type="text" 
                                name="matricula" 
                                id="inputMatricula"
                                placeholder="AA-00-AA" 
                                maxlength="8"
                                oninput="formatarMatricula(this)"
                                required
                            >
                        </div>
                    </div>
                    <button type="submit" name="btn_add_veiculo" class="btn-submit-car">Guardar Veículo</button>
                </form>
            </div>

            <!-- Tabela de Veículos -->
            <?php if ($veiculos && $veiculos->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Matrícula</th>
                            <th style="width: 80px; text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($v = $veiculos->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php 
                                    $nomeTipo = strtolower($v['nome_tipo_veiculo']);
                                    if(strpos($nomeTipo, 'moto') !== false || strpos($nomeTipo, 'mota') !== false) {
                                        echo '<span class="badge badge-warning">🛵 Mota</span>';
                                    } else {
                                        echo '<span class="badge badge-info">🚗 Carro</span>';
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($v['marca_veiculos']) ?></td>
                            <td><?= htmlspecialchars($v['modelo_veiculos']) ?></td>
                            <td><span class="matricula"><?= htmlspecialchars($v['matricula_veiculos']) ?></span></td>
                            <td style="text-align: center;">
                                <form method="POST" onsubmit="return confirm('Tem a certeza que deseja remover este veículo?');">
                                    <input type="hidden" name="id_veiculo_remove" value="<?= $v['id_veiculos'] ?>">
                                    <button type="submit" name="btn_remove_veiculo" class="btn-remove" title="Remover Veículo">
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
            <?php if ($cartoes && $cartoes->num_rows > 0): ?>
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
            <?php if ($historico && $historico->num_rows > 0): ?>
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

    <?php endif; ?>

</div>

<?php include('footer.php'); ?>

<script>
    function toggleCarForm() {
        var form = document.getElementById('form-Veiculos');
        if (form) {
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    }

    // --- NOVA FUNÇÃO PARA FORMATAR MATRÍCULA AUTOMATICAMENTE ---
    function formatarMatricula(input) {
        // 1. Pega no valor, mete maiúsculas e remove tudo o que não é letra/número
        let valor = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        
        // 2. Adiciona o primeiro traço depois do 2º caracter
        if (valor.length > 2) {
            valor = valor.substring(0, 2) + '-' + valor.substring(2);
        }
        
        // 3. Adiciona o segundo traço depois do 5º caracter (contando com o traço anterior)
        if (valor.length > 5) {
            valor = valor.substring(0, 5) + '-' + valor.substring(5, 7);
        }
        
        // 4. Atualiza o input visualmente
        input.value = valor;
    }
</script>

</body>
</html>