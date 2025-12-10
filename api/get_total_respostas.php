<?php
$conn = new mysqli("localhost", "root", "", "easypark");
if ($conn->connect_error)
    die("ERROR");

// Conta o total de respostas no formulário
$sql = "SELECT COUNT(*) as total FROM formulario";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo "SUCCESS|" . $row['total'];
$conn->close();
?>