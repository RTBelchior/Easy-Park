<?php
// Arquivo de diagnóstico - NÃO usar em produção

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico - Entradas do Dia</h1>";
echo "<hr>";

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

// 1. Verificar conexão
echo "<h2>1. Testando Conexão</h2>";
$conn = new mysqli($host, $utilizador, $senha, $dbname);

if ($conn->connect_error) {
    echo "❌ Erro de conexão: " . $conn->connect_error . "<br>";
    die();
} else {
    echo "✅ Conexão OK<br>";
}

// 2. Verificar se a tabela existe
echo "<h2>2. Verificando Tabela</h2>";
$result = $conn->query("SHOW TABLES LIKE 'historico_acesso'");
if ($result->num_rows > 0) {
    echo "✅ Tabela 'historico_acesso' existe<br>";
} else {
    echo "❌ Tabela 'historico_acesso' NÃO existe<br>";
}

// 3. Contar registros totais
echo "<h2>3. Total de Registros</h2>";
$result = $conn->query("SELECT COUNT(*) as total FROM historico_acesso");
$row = $result->fetch_assoc();
echo "📊 Total de registros: " . $row['total'] . "<br>";

// 4. Contar entradas
echo "<h2>4. Total de Entradas</h2>";
$result = $conn->query("SELECT COUNT(*) as total FROM historico_acesso WHERE tipo_acesso = 'entrada'");
$row = $result->fetch_assoc();
echo "🚗 Total de entradas: " . $row['total'] . "<br>";

// 5. Entradas de hoje
echo "<h2>5. Entradas de HOJE</h2>";
$result = $conn->query("
    SELECT COUNT(*) as total_entradas
    FROM historico_acesso
    WHERE tipo_acesso = 'entrada'
    AND DATE(data_hora) = CURDATE()
");
$row = $result->fetch_assoc();
echo "📅 Data de hoje: " . date('Y-m-d') . "<br>";
echo "🚗 Entradas hoje: " . $row['total_entradas'] . "<br>";

// 6. Últimos registros
echo "<h2>6. Últimos 10 Registros</h2>";
$result = $conn->query("
    SELECT id_acesso, tipo_acesso, DATE(data_hora) as data, TIME(data_hora) as hora
    FROM historico_acesso
    ORDER BY data_hora DESC
    LIMIT 10
");

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Tipo</th><th>Data</th><th>Hora</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id_acesso'] . "</td>";
        echo "<td>" . $row['tipo_acesso'] . "</td>";
        echo "<td>" . $row['data'] . "</td>";
        echo "<td>" . $row['hora'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "⚠️ Nenhum registro encontrado<br>";
}

// 7. Testar JSON
echo "<h2>7. Teste de JSON</h2>";
$data = [
    'success' => true,
    'total_entradas' => $row['total_entradas'] ?? 0,
    'data' => date('Y-m-d')
];
echo "<pre>" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

// 8. Link para API
echo "<h2>8. Teste de API</h2>";
echo "<a href='get_entradas_dia.php' target='_blank'>🔗 Abrir API get_entradas_dia.php</a><br>";

$conn->close();
?>