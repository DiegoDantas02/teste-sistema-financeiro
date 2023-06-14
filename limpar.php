<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projeto_software";

// Conexão com o banco de dados
$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query para excluir todas as transações
$query = $conn->prepare("DELETE FROM transacoes");
$query->execute();

// Redirecionamento de volta para a página index.php
header("Location: index.php");
exit();
?>
