<?php
// Configurações de conexão
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "easypark";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

echo "<h1>Iniciando migração de senhas...</h1>";

// 1. Buscar todos os utilizadores
$sql = "SELECT id_utilizador, nome_utilizador, password_utilizador FROM utilizadores";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id = $row['id_utilizador'];
        $pass_atual = $row['password_utilizador'];
        $nome = $row['nome_utilizador'];

        // VERIFICAÇÃO DE SEGURANÇA:
        // Se a senha já tiver 60 caracteres e começar por "$2y$", provavelmente já é um hash.
        // Isto impede que encriptes uma senha que já está encriptada (o que estragaria o login).
        if (strlen($pass_atual) == 60 && substr($pass_atual, 0, 4) === '$2y$') {
            echo "<p style='color:orange'>Ignorado (já parece encriptado): $nome</p>";
            continue;
        }

        // 2. Criar o Hash seguro
        $novo_hash = password_hash($pass_atual, PASSWORD_DEFAULT);

        // 3. Atualizar na Base de Dados
        $update_sql = "UPDATE utilizadores SET password_utilizador = ? WHERE id_utilizador = ?";
        
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $novo_hash, $id);
        
        if ($stmt->execute()) {
            echo "<p style='color:green'>Sucesso: A senha de <strong>$nome</strong> foi encriptada.</p>";
        } else {
            echo "<p style='color:red'>Erro ao atualizar $nome: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
    echo "<h2>Migração concluída! Podes testar o login agora.</h2>";
    echo "<p><strong>IMPORTANTE:</strong> Apaga este ficheiro (migrar_senhas.php) depois de verificares que tudo funciona.</p>";
} else {
    echo "Nenhum utilizador encontrado.";
}

$conn->close();
?>