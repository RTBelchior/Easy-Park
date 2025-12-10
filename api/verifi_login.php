<?php
session_start();

// Configurações de conexão (Idealmente, coloca isto num ficheiro separado db_connect.php)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easypark";

// Tenta conectar
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Habilita reporte de erros do MySQLi

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4"); // utf8mb4 é mais seguro e completo que utf8
} catch (mysqli_sql_exception $e) {
    // Log do erro real no servidor (não mostrar ao utilizador)
    error_log("Erro de conexão: " . $e->getMessage());
    $_SESSION['login_erro'] = "Serviço temporariamente indisponível.";
    header("Location: ../paginas/login.php");
    exit();
}

// Verifica se o método é POST (Impede acesso direto pela URL)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../paginas/login.php");
    exit();
}

// Receber dados e limpar
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password_input = $_POST['password'] ?? '';

// Guarda email na sessão para não obrigar o user a digitar tudo de novo em caso de erro
$_SESSION['temp_email'] = $email;

// 1. Validação de Campos Vazios
if (empty($email) || empty($password_input)) {
    $_SESSION['login_erro'] = "Por favor, preencha todos os campos.";
    header("Location: ../paginas/login.php");
    exit();
}

// 2. Validação de Formato de Email (Mais robusta)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_erro'] = "Formato de email inválido.";
    header("Location: ../paginas/login.php");
    exit();
}

// 3. Validação de Domínio IPS
// Aceita @estudantes.ips.pt, @estsetubal.ips.pt, @ips.pt, etc.
if (!preg_match("/^.+@(estudantes\.ips\.pt|estsetubal\.ips\.pt|ips\.pt)$/", $email)) {
    $_SESSION['login_erro'] = "Utilize um email institucional válido do IPS.";
    header("Location: ../paginas/login.php");
    exit();
}

// 4. Buscar utilizador na Base de Dados
$sql = "SELECT id_utilizador, nome_utilizador, id_tipo_utilizador, email_utilizador, password_utilizador, ativo_utilizador 
        FROM utilizadores 
        WHERE email_utilizador = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// --- ZONA DE SEGURANÇA ---
// Para segurança, se o email não existir ou a password estiver errada, 
// a mensagem deve ser genérica para não revelar quais emails existem no sistema.

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // 5. Verificar Senha
    // Se usares hash na BD (RECOMENDADO):
    $senha_valida = password_verify($password_input, $user['password_utilizador']);
    
    // --- MODO INSEGURO (APENAS SE NÃO TIVERES COMO ALTERAR A BD AGORA) ---
    // Se a tua base de dados tem senhas em texto puro (ex: "1234"), 
    // comenta a linha acima e descomenta a linha abaixo. 
    // $senha_valida = ($password_input === $user['password_utilizador']);

    if ($senha_valida) {
        
        // 6. Verificar se está ativo
        if ($user['ativo_utilizador'] == 0) {
            $_SESSION['login_erro'] = "A conta está desativada. Contacte a administração.";
            header("Location: ../paginas/login.php");
            exit();
        }

        // --- SUCESSO ---
        
        // Regenerar ID da sessão (Proteção contra Session Fixation)
        session_regenerate_id(true);

        // Limpar erros e dados temporários
        unset($_SESSION['login_erro']);
        unset($_SESSION['temp_email']);

        // Guardar dados na sessão
        $_SESSION['id_utilizador'] = $user['id_utilizador'];
        $_SESSION['nome'] = $user['nome_utilizador'];
        $_SESSION['tipo'] = $user['id_tipo_utilizador'];
        $_SESSION['email'] = $user['email_utilizador'];
        $_SESSION['logado'] = true;

        $stmt->close();
        $conn->close();

        // Redirecionamento final
        header("Location: ../index.php");
        exit();
    }
}

// Se chegou aqui, ou o email não existe ou a senha está errada
$_SESSION['login_erro'] = "Email ou palavra-passe incorretos.";
header("Location: ../paginas/login.php");
exit();
?>