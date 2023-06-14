<?php
// Verifica se foi fornecido um ID válido
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Conecte-se ao banco de dados
    // Substitua as informações do banco de dados pelos seus próprios dados de conexão
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projeto_software";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Excluir a transação do banco de dados com base no ID fornecido
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM transacoes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Redirecionar para a página inicial após a exclusão
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao excluir a transação: " . $e->getMessage();
    }

    // Fechar conexão com o banco de dados
    $conn = null;
} else {
    echo "ID inválido";
}
?>
