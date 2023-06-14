<?php

$host = 'localhost'; // Endereço do servidor de banco de dados
$db = 'projeto_software'; // Nome do banco de dados
$user = 'root'; // Nome do usuário do banco de dados
$password = ''; // Senha do usuário do banco de dados

try {
    // Conexão com o banco de dados usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    
    // Definir o modo de erro do PDO para Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Exemplo de consulta
    $query = "SELECT * FROM tabela";
    $result = $conn->query($query);
    
    // Processar os resultados
    foreach ($result as $row) {
        echo $row['coluna1'] . " - " . $row['coluna2'] . "<br>";
    }
} catch (PDOException $e) {
    echo "Falha na conexão com o banco de dados: " . $e->getMessage();
    exit;
}

?>
