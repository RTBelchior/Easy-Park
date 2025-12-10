<?php
header('Content-Type: text/plain; charset=utf-8');
session_start();

$host = "localhost";
$utilizador = "root";
$senha = "";
$dbname = "easypark";

try {
    if (!isset($_SESSION['id_utilizador'])) {
        die("ERROR|Não autorizado");
    }

    $conn = new mysqli($host, $utilizador, $senha, $dbname);
    if ($conn->connect_error) {
        die("ERROR|Erro conexão DB");
    }
    $conn->set_charset("utf8mb4");

    $userId = $_SESSION['id_utilizador'];

    // Buscar veículos
    $sql = "
        SELECT v.id_veiculos, v.marca_veiculos, v.modelo_veiculos, v.matricula_veiculos, tv.nome_tipo_veiculo
        FROM veiculos v
        INNER JOIN veiculos_utilizador vu ON v.id_veiculos = vu.id_veiculos
        INNER JOIN tipo_veiculo tv ON v.id_tipo_veiculo = tv.id_tipo_veiculo
        WHERE vu.id_utilizador = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $listaVeiculos = [];

    while ($row = $result->fetch_assoc()) {
        // Formato linha: ID|MARCA|MODELO|MATRICULA|TIPO
        $linha = $row['id_veiculos'] . '|' .
            $row['marca_veiculos'] . '|' .
            $row['modelo_veiculos'] . '|' .
            $row['matricula_veiculos'] . '|' .
            $row['nome_tipo_veiculo'];

        $listaVeiculos[] = $linha;
    }

    // Junta tudo com ponto e vírgula
    // Exemplo final: SUCCESS|1|BMW|S1|AA-00-AA|Carro;2|Honda|CB|BB-22-BB|Mota
    echo "SUCCESS|" . implode(';', $listaVeiculos);

} catch (Exception $e) {
    echo "ERROR|" . $e->getMessage();
}
?>