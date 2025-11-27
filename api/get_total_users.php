<?php
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = '127.0.0.1';
$port = '3307';
$db   = 'easypark';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Contar total de utilizadores ativos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM utilizadores WHERE ativo_utilizador = 1");
    $row = $stmt->fetch();
    $total_ativos = $row['total'];
    
    // Contar total geral (incluindo inativos)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM utilizadores");
    $row = $stmt->fetch();
    $total_geral = $row['total'];
    
    // Contar por tipo
    $stmt = $pdo->query("
        SELECT 
            tu.tipo_utilizador,
            COUNT(*) as total
        FROM utilizadores u
        INNER JOIN tipo_utilizador tu ON u.id_tipo_utilizador = tu.id_tipo_utilizador
        WHERE u.ativo_utilizador = 1
        GROUP BY tu.tipo_utilizador
    ");
    $tipos = $stmt->fetchAll();
    
    // Formatar contagem por tipo: Administrador:1,Aluno:2,Funcionário:1,Professor:1
    $tipos_formatados = [];
    foreach ($tipos as $tipo) {
        $tipos_formatados[] = $tipo['tipo_utilizador'] . ':' . $tipo['total'];
    }
    
    // Retorna: SUCCESS|total_ativos|total_geral|tipos
    // Exemplo: SUCCESS|4|4|Administrador:1,Aluno:1,Funcionário:1,Professor:1
    echo "SUCCESS|" . $total_ativos . "|" . $total_geral . "|" . implode(',', $tipos_formatados);

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>