<?php
// Conexão com o banco de dados
$conn = new PDO('mysql:host=localhost;dbname=projeto_software', 'root', '');

// Verificar se foi enviado um ID de transação para edição
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tipo = $_POST['tipo'];
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];

        // Atualizar a transação no banco de dados
        $stmt = $conn->prepare('UPDATE transacoes SET tipo = ?, descricao = ?, valor = ? WHERE id = ?');
        $stmt->execute([$tipo, $descricao, $valor, $id]);

        // Redirecionar para a página inicial
        header('Location: index.php');
        exit;
    }

    // Obter os dados da transação a ser editada
    $stmt = $conn->prepare('SELECT * FROM transacoes WHERE id = ?');
    $stmt->execute([$id]);
    $transacao = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Redirecionar para a página inicial se o ID não for fornecido
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Sistema de Controle de Finanças - Editar Transação</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="transacoes.php">Transações</a></li>
                <li><a href="#">Relatórios</a></li>
                <li><a href="#">Configurações</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Sistema de Controle de Finanças</h1>

        <div class="formulario">
            <h2>Editar Transação:</h2>
            <form method="POST">
                <select name="tipo">
                    <option value="receita" <?php if ($transacao['tipo'] === 'receita') echo 'selected'; ?>>Receita</option>
                    <option value="despesa" <?php if ($transacao['tipo'] === 'despesa') echo 'selected'; ?>>Despesa</option>
                </select>
                <input type="text" name="descricao" placeholder="Descrição" value="<?php echo $transacao['descricao']; ?>" required>
                <input type="number" name="valor" step="0.01" placeholder="Valor" value="<?php echo $transacao['valor']; ?>" required>
                <button type="submit">Salvar</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Sistema de Controle de Finanças</p>
    </footer>
</body>

</html>
