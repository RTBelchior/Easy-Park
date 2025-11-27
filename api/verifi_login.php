<?php
session_start();

// Configurações de conexão
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easypark";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $_SESSION['login_erro'] = "Erro interno do servidor.";
    header("Location: ../paginas/login.php");
    exit();
}

$conn->set_charset("utf8");

// Receber dados e limpar
$email = trim($_POST['email'] ?? '');
$password_input = trim($_POST['password'] ?? '');

// 1. Validação de Campos Vazios
if (empty($email) || empty($password_input)) {
    $_SESSION['login_erro'] = "Por favor, preencha todos os campos.";
    header("Location: ../paginas/login.php");
    exit();
}

// 2. Validação de Formato de Email (ATUALIZADA)
// Agora permite: qualquercoisa@estudantes.ips.pt OU qualquercoisa@estsetubal.ips.pt
if (!preg_match("/^.+@(estudantes\.ips\.pt|estsetubal\.ips\.pt)$/", $email)) {
    $_SESSION['login_erro'] = "Utilize um email institucional válido (IPS).";
    header("Location: ../paginas/login.php");
    exit();
}

// 3. Buscar utilizador na Base de Dados
// NOTA: Ajustei os nomes das colunas para bater certo com a tua BD (ex: email_utilizador)
$sql = "SELECT id_utilizador, nome_utilizador, id_tipo_utilizador, email_utilizador, password_utilizador, ativo_utilizador 
        FROM utilizadores 
        WHERE email_utilizador = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Se não encontrou o email
if ($result->num_rows === 0) {
    $_SESSION['login_erro'] = "Email não encontrado.";
    header("Location: ../paginas/login.php");
    exit();
}

$user = $result->fetch_assoc();

// 4. Verificar se está ativo (ativo_utilizador)
if ($user['ativo_utilizador'] == 0) {
    $_SESSION['login_erro'] = "A sua conta está desativada. Contacte a administração.";
    header("Location: ../paginas/login.php");
    exit();
}

// 5. Verificar Senha (password_utilizador)
if ($password_input !== $user['password_utilizador']) {
    $_SESSION['login_erro'] = "Palavra-passe incorreta.";
    header("Location: ../paginas/login.php");
    exit();
}

// --- SUCESSO ---

// Limpar erros antigos
unset($_SESSION['login_erro']);

// Guardar dados na sessão (Mantendo os nomes de sessão simples para usares no site)
$_SESSION['id_utilizador'] = $user['id_utilizador'];
$_SESSION['nome'] = $user['nome_utilizador'];
$_SESSION['tipo'] = $user['id_tipo_utilizador']; // Guarda o ID do tipo (1, 2, 3...)
$_SESSION['email'] = $user['email_utilizador'];
$_SESSION['logado'] = true;

$stmt->close();
$conn->close();

// --- REDIRECIONAMENTO ---
header("Location: ../index.php");
exit();
?>