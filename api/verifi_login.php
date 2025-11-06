<?php
session_start();

// Mostrar erros (só pra debug)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexão ao banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easypark";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Dados do formulário
$email = trim($_POST['email'] ?? '');
$password_input = trim($_POST['password'] ?? '');

// Validação básica
if (empty($email) || empty($password_input)) {
    echo "<script>alert('Por favor, preencha todos os campos.');window.location.href='../paginas/login.php';</script>";
    exit();
}

// Verifica se o email é institucional ou admin
if (!preg_match("/^[0-9]+@estudantes\.ips\.pt$/", $email) && $email !== "admin@estudantes.ips.pt") {
    echo "<script>alert('Use apenas o email institucional (ex: numero@estudantes.ips.pt)');window.location.href='../paginas/login.php';</script>";
    exit();
}

// Verifica utilizador no banco
$sql = "SELECT id_utilizador, nome, tipo, email, password, ativo FROM utilizadores WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('O seu email não está registado. Contacte a administração.');window.location.href='../paginas/login.php';</script>";
    exit();
}

$user = $result->fetch_assoc();

// Verifica se está ativo
if (!$user['ativo']) {
    echo "<script>alert('Utilizador desativado. Contacte a administração.');window.location.href='../paginas/login.php';</script>";
    exit();
}

// Verifica senha
if ($password_input !== $user['password']) {
    echo "<script>alert('Palavra-passe incorreta!');window.location.href='../paginas/login.php';</script>";
    exit();
}

// Login bem-sucedido
$_SESSION['id_utilizador'] = $user['id_utilizador'];
$_SESSION['nome'] = $user['nome'];
$_SESSION['tipo'] = $user['tipo'];
$_SESSION['email'] = $user['email'];
$_SESSION['logado'] = true;

$stmt->close();
$conn->close();

// Redireciona
if (strtolower(trim($user['tipo'])) === 'administrador') {
    header("Location: ../paginas/administracao.php");
    exit();
} else {
    header("Location: ../index.php"); // aqui o header.php mostrará "Bem-vindo, nome"
    exit();
}
?>
