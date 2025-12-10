<?php
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuração da ligação
$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    // --- LIGAÇÃO NO FORMATO PEDIDO ---
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Não autenticado");
    }
    
    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        die("ERROR|Falha na conexão: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    // --------------------------------

    // 1. Contar total de utilizadores ativos
    $sqlAtivos = "SELECT COUNT(*) as total FROM utilizadores WHERE ativo_utilizador = 1";
    $resAtivos = $conn->query($sqlAtivos);
    $rowAtivos = $resAtivos->fetch_assoc();
    $total_ativos = $rowAtivos['total'];
    
    // 2. Contar total geral (incluindo inativos)
    $sqlGeral = "SELECT COUNT(*) as total FROM utilizadores";
    $resGeral = $conn->query($sqlGeral);
    $rowGeral = $resGeral->fetch_assoc();
    $total_geral = $rowGeral['total'];
    
    // 3. Contar por tipo
    $sqlTipos = "
        SELECT 
            tu.tipo_utilizador,
            COUNT(*) as total
        FROM utilizadores u
        INNER JOIN tipo_utilizador tu ON u.id_tipo_utilizador = tu.id_tipo_utilizador
        WHERE u.ativo_utilizador = 1
        GROUP BY tu.tipo_utilizador
    ";
    $resTipos = $conn->query($sqlTipos);
    
    // Formatar contagem por tipo: Administrador:1,Aluno:2,...
    $tipos_formatados = [];
    if ($resTipos) {
        while ($row = $resTipos->fetch_assoc()) {
            $tipos_formatados[] = $row['tipo_utilizador'] . ':' . $row['total'];
        }
    }
    
    // Retorna: SUCCESS|total_ativos|total_geral|tipos
    echo "SUCCESS|" . $total_ativos . "|" . $total_geral . "|" . implode(',', $tipos_formatados);
    
    $conn->close();

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>