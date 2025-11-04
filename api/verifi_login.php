<?php
session_start();

// Mostrar erros (podes remover depois)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuração da base de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easypark";

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$email = trim($_POST['email'] ?? '');
$password_input = trim($_POST['password'] ?? '');

// Validação básica
if (empty($email) || empty($password_input)) {
    echo "<script>alert('Por favor, preencha todos os campos.');window.location.href='../paginas/login.php';</script>";
    exit();
}

// Verifica se o email é institucional, mas permite admin
if (!preg_match("/^[0-9]+@estudantes\.ips\.pt$/", $email) && $email !== "admin@estudantes.ips.pt") {
    echo "<script>alert('Use apenas o email institucional (ex: numero@estudantes.ips.pt)');window.location.href='../paginas/login.php';</script>";
    exit();
}

// Procura o utilizador pelo email
$sql = "SELECT id_utilizador, nome, tipo, email, password, ativo FROM utilizadores WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o utilizador existe
if ($result->num_rows === 0) {
    echo "<script>alert('O seu email não está registado. Contacte a administração.');window.location.href='../paginas/login.php';</script>";
    $stmt->close();
    $conn->close();
    exit();
}

$user = $result->fetch_assoc();

// Verifica se a conta está ativa (boolean)
if (!$user['ativo']) {
    echo "<script>alert('Utilizador desativado. Contacte a administração.');window.location.href='../paginas/login.php';</script>";
    $stmt->close();
    $conn->close();
    exit();
}

// Verifica a password (texto simples)
if ($password_input !== $user['password']) {
    echo "<script>alert('Palavra-passe incorreta!');window.location.href='../paginas/login.php';</script>";
    $stmt->close();
    $conn->close();
    exit();
}

// Login bem-sucedido - criar sessão
$_SESSION['id_utilizador'] = $user['id_utilizador'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['tipo'] = $user['tipo'];
$_SESSION['email'] = $user['email'];
$_SESSION['logado'] = true;

$stmt->close();
$conn->close();

// Redireciona conforme o tipo de utilizador
// Verifica o valor do enum 'tipo'
if (strtolower(trim($user['tipo'])) === 'administrador') {
    header("Location: ../paginas/administracao.php");
    exit();
} else {
    header("Location: ../index.html");
    exit();
}
?>