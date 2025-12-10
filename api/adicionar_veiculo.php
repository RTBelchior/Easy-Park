<?php
// Limpar qualquer output anterior e definir cabeçalhos
ob_clean();
header('Content-Type: text/plain; charset=utf-8');

// Desativar exibição de erros no output (evita estragar o formato para o JS)
error_reporting(0);
ini_set('display_errors', 0);

session_start();

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

// Função auxiliar para enviar resposta e sair
function enviarResposta($status, $mensagem) {
    echo $status . "|" . $mensagem;
    exit;
}

try {
    // 1. Verificações de Segurança
    if (!isset($_SESSION['id_utilizador'])) {
        enviarResposta("ERROR", "Sessão expirada. Faça login novamente.");
    }
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        enviarResposta("ERROR", "Método inválido.");
    }

    $userId = $_SESSION['id_utilizador'];

    // 2. Receber dados
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $idTipo = intval($_POST['id_tipo_veiculo'] ?? 0);
    $matriculaRaw = $_POST['matricula'] ?? '';

    // 3. Validações Básicas
    if (empty($marca) || empty($modelo) || $idTipo <= 0 || empty($matriculaRaw)) {
        enviarResposta("ERROR", "Preencha todos os campos corretamente.");
    }

    // 4. Tratar Matrícula
    $matriculaLimpa = preg_replace('/[^A-Z0-9]/', '', strtoupper($matriculaRaw));

    if (strlen($matriculaLimpa) !== 6) {
        enviarResposta("ERROR", "Matrícula inválida. Deve ter 6 caracteres.");
    }

    $parte1 = substr($matriculaLimpa, 0, 2);
    $parte2 = substr($matriculaLimpa, 2, 2);
    $parte3 = substr($matriculaLimpa, 4, 2);
    $matriculaFinal = "$parte1-$parte2-$parte3";

    // 5. Conexão DB
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        enviarResposta("ERROR", "Erro de conexão à base de dados.");
    }
    $conn->set_charset("utf8mb4");

    // 6. VERIFICAR DUPLICADOS (Crucial)
    $sqlCheck = "SELECT id_veiculos FROM veiculos WHERE matricula_veiculos = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $matriculaFinal);
    $stmtCheck->execute();
    $stmtCheck->store_result(); // <--- OBRIGATÓRIO para num_rows funcionar

    if ($stmtCheck->num_rows > 0) {
        $stmtCheck->close();
        $conn->close();
        enviarResposta("ERROR", "Esta matrícula já está registada no sistema.");
    }
    $stmtCheck->close();

    // 7. Inserir Veículo
    $sqlInsert = "INSERT INTO veiculos (marca_veiculos, modelo_veiculos, matricula_veiculos, id_tipo_veiculo) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("sssi", $marca, $modelo, $matriculaFinal, $idTipo);

    if ($stmtInsert->execute()) {
        $novoIdVeiculo = $conn->insert_id;

        // 8. Associar ao Utilizador
        $sqlLink = "INSERT INTO veiculos_utilizador (id_veiculos, id_utilizador) VALUES (?, ?)";
        $stmtLink = $conn->prepare($sqlLink);
        $stmtLink->bind_param("ii", $novoIdVeiculo, $userId);

        if ($stmtLink->execute()) {
            enviarResposta("SUCCESS", "Veículo adicionado com sucesso!");
        } else {
            enviarResposta("ERROR", "Erro ao associar viatura.");
        }
    } else {
        // Se falhar, verifica se foi erro de duplicado (Código 1062)
        if ($conn->errno == 1062) {
            enviarResposta("ERROR", "Esta matrícula já existe (Erro DB).");
        } else {
            enviarResposta("ERROR", "Erro ao registar: " . $conn->error);
        }
    }

    $conn->close();

} catch (Exception $e) {
    enviarResposta("ERROR", $e->getMessage());
}
?>