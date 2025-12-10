<?php
$conn = new mysqli("localhost", "root", "", "easypark");
if ($conn->connect_error)
    die("ERROR");

// Conta o total de carros na tabela
$sql = "SELECT COUNT(*) as total FROM veiculos";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo "SUCCESS|" . $row['total'];
$conn->close();
?>