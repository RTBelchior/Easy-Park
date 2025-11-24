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

// 2. Validação de Formato de Email
if (!preg_match("/^[0-9]+@estudantes\.ips\.pt$/", $email) && $email !== "admin@estudantes.ips.pt") {
    $_SESSION['login_erro'] = "Utilize apenas o email institucional (@estudantes.ips.pt).";
    header("Location: ../paginas/login.php");
    exit();
}

// 3. Buscar utilizador na Base de Dados
$sql = "SELECT id_utilizador, nome, tipo, email, password, ativo FROM utilizadores WHERE email = ?";
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

// 4. Verificar se está ativo
if ($user['ativo'] == 0) {
    $_SESSION['login_erro'] = "A sua conta está desativada. Contacte a administração.";
    header("Location: ../paginas/login.php");
    exit();
}

// 5. Verificar Senha
if ($password_input !== $user['password']) {
    $_SESSION['login_erro'] = "Palavra-passe incorreta.";
    header("Location: ../paginas/login.php");
    exit();
}

// --- SUCESSO ---

// Limpar erros antigos
unset($_SESSION['login_erro']);

// Guardar dados na sessão
$_SESSION['id_utilizador'] = $user['id_utilizador'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['tipo'] = $user['tipo'];
$_SESSION['email'] = $user['email'];
$_SESSION['logado'] = true;

$stmt->close();
$conn->close();

// --- REDIRECIONAMENTO ---
// Agora todos vão para a index.php
header("Location: ../index.php");
exit();
?>