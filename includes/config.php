<?php
$host = 'localhost'; // endereço do servidor de banco de dados
$db = 'projeto_software'; // nome do banco de dados
$user = 'root'; // nome do usuário do banco de dados
$password = ''; // senha do usuário do banco de dados

// Conexão com o banco de dados
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
?>
