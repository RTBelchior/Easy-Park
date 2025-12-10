<?php
session_start();

if (!isset($_SESSION['id_utilizador'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";
$tipos_veiculo = [];

try {
    $conn = @new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        // Se falhar conexão aqui, apenas não carrega o select, não mata a página toda
        error_log("Erro DB Perfil: " . $conn->connect_error);
    } else {
        $conn->set_charset("utf8mb4");
        $sqlTipos = "SELECT id_tipo_veiculo, nome_tipo_veiculo FROM tipo_veiculo";
        $resTipos = $conn->query($sqlTipos);
        if ($resTipos) {
            while ($row = $resTipos->fetch_assoc()) {
                $tipos_veiculo[] = $row;
            }
        }
        $conn->close();
    }
} catch (Exception $e) {
    // Erro silencioso no PHP, JS trata o resto
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>Perfil - EasyPark</title>
    <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/perfil.css">
</head>

<body>
    <?php include('header.php'); ?>

    <div class="container">

        <!-- CARD PERFIL -->
        <div class="card">
            <h2>👤 Informações Pessoais</h2>
            <p><strong>Nome:</strong> <span id="user-nome" class="loading-text">A carregar...</span></p>
            <p><strong>Perfil:</strong> <span id="user-tipo" class="loading-text">--</span></p>
            <p><strong>Email:</strong> <span id="user-email" class="loading-text">--</span></p>
            <p><strong>Estado da Conta:</strong> <span id="user-estado" class="loading-text">--</span></p>
        </div>

        <!-- VEÍCULOS -->
        <div class="card">
            <div class="card-header-flex">
                <h2>🚘 Os Meus Veículos</h2>
                <button class="btn-add" onclick="toggleCarForm()">+ Adicionar Veículo</button>
            </div>

            <!-- Formulário (Escondido) -->
            <div class="car-form-container" id="form-Veiculos">
                <div id="msg-form-veiculo" style="display:none;"></div>
                
                <form id="form-adicionar-veiculo">
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
                        
                        <div class="form-group">
                            <label style="display:block; margin-bottom:5px; font-size:13px; color:#64748b;">Matrícula</label>
                            <input type="text" name="matricula" placeholder="AA-00-AA" maxlength="8" oninput="formatarMatricula(this)" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit-car">Guardar Veículo</button>
                </form>
            </div>

            <!-- Tabela de Veículos -->
            <table id="tabela-veiculos" style="display:none;">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Matrícula</th>
                        <th style="width: 80px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody id="lista-veiculos-body">
                </tbody>
            </table>
            
            <p id="msg-no-veiculos" style="color: #64748b; margin-top: 15px; display:none;">
                Nenhum veículo associado a este perfil.
            </p>
        </div>

        <!-- CARD CARTÕES -->
        <div class="card">
            <h2>💳 Os Meus Cartões</h2>
            <table id="tabela-cartoes" style="width:100%; border-collapse:collapse; margin-top:10px;">
                <thead>
                    <tr style="background:#f1f5f9; text-align:left;">
                        <th style="padding:15px;">Tipo</th>
                        <th style="padding:15px;">Número / ID</th>
                        <th style="padding:15px;">Estado</th>
                        <th style="padding:15px;">Data Registo</th>
                    </tr>
                </thead>
                <tbody id="lista-cartoes-body">
                    <tr>
                        <td colspan="4" style="text-align:center; padding:15px; color:#64748b;">
                            A carregar dados...
                        </td>
                    </tr>
                </tbody>
            </table>
            <p id="msg-no-card" style="display:none; color: #64748b; margin-top:15px;">
                Não tem cartões associados.
            </p>
        </div>

    </div>

    <?php include('footer.php'); ?>
    <script src="../js/perfil.js"></script>
</body>

</html>