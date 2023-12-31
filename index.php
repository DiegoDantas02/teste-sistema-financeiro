<?php
// Conexão com o banco de dados
$conn = new PDO('mysql:host=localhost;dbname=projeto_software', 'root', '');

// Função para obter o saldo atual
function getSaldo()
{
    global $conn;

    $query = $conn->query('SELECT COALESCE(SUM(valor), 0) as saldo FROM transacoes');
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['saldo'];
}

// Função para obter o total de gastos
function getTotalGastos()
{
    global $conn;

    $query = $conn->query('SELECT COALESCE(SUM(valor), 0) as total_gastos FROM transacoes WHERE tipo = "despesa"');
    $row = $query->fetch(PDO::FETCH_ASSOC);

    return $row['total_gastos'];
}

// Função para obter o restante do salário
function getRestanteSalario()
{
    $saldo = getSaldo();
    $totalGastos = getTotalGastos();
    $restante = $saldo - $totalGastos;

    return $restante;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    // Inserir nova transação no banco de dados
    $stmt = $conn->prepare('INSERT INTO transacoes (tipo, descricao, valor) VALUES (?, ?, ?)');
    $stmt->execute([$tipo, $descricao, $valor]);

    // Redirecionar para a página inicial
    header('Location: index.php');
    exit;
}

// Verificar se foi enviado um ID de transação para exclusão
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Excluir transação do banco de dados
    $stmt = $conn->prepare('DELETE FROM transacoes WHERE id = ?');
    $stmt->execute([$id]);

    // Redirecionar para a página inicial
    header('Location: index.php');
    exit;
}
?>

<!-- ------------------------ -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Controle de Finanças</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<!-- logo -->
<a class="navbar-brand" href="index.php"><img src="logo.png" alt="Logo" class="logo "></a>

<!--  -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transacoes.php">Transações</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="#">Configurações</a>
                    </li>
                </ul>
            </div>
            <div class="ml-auto">
                <button class="btn btn-danger">Sair</button>
            </div>
        </nav>
    </header>
    <!-- cabeçalho -->
<br>
<div class="container">
    <h1 class="text-center">Sistema de Controle de Finanças</h1>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 text-center saldo-card">
                <div class="card-body">
                    <h2 class="card-title">📊 Saldo Atual </h2>
                    <p class="card-text">Saldo Total: R$ <?php echo number_format(getSaldo(), 2, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 text-center gastos-card">
                <div class="card-body">
                    <h2 class="card-title">📉 Total de Gastos </h2>
                    <p class="card-text">R$ <?php echo number_format(getTotalGastos(), 2, ',', '.'); ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 text-center restante-card">
                <div class="card-body">
                    <h2 class="card-title">📈 Receitas </h2>
                    <p class="card-text">R$ <?php echo number_format(getRestanteSalario(), 2, ',', '.'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if (getRestanteSalario() < (getSaldo() * 0.5)) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                Você está gastando demais! Cuidado com suas despesas.
            </div>
        </div>
    </div>
    <?php } ?>
</div>


        <!-- ------------- -->
        <div class="card mb-4">
    <div class="card-body">
        <h2 class="card-title">Histórico de Transações:</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Descrição</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $conn->query('SELECT * FROM transacoes ORDER BY id DESC');
                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                        $descricao = $row['descricao'];
                        $valor = $row['valor'];
                        $sinal = $valor >= 0 ? '+' : '-';
                        $valorFormatado = number_format(abs($valor), 2, ',', '.');
                        echo "<tr>";
                        echo "<td>$descricao</td>";
                        echo "<td>$sinal R$ $valorFormatado</td>";
                        echo "<td><a href='editar.php?id=" . $row['id'] . "' class='btn btn-primary'>Editar</a> <a href='excluir.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Deseja realmente excluir essa transação?\")'>Excluir</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <form method="POST" action="limpar.php">
            <button type="submit" class="btn btn-danger">Limpar Tudo</button>
        </form>
    </div>
</div>
<!-- ----------- -->

<!-- ----------- -->
<div class="card">
    <div class="card-body">
        <h2 class="card-title">Adicionar Transação</h2>
        <form method="POST">
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="receita">Receita</option>
                    <option value="receita">+Salario</option>
                    <option value="despesa">-Despesa</option>
                    <option value="despesa">-Alimentação</option>
                    <option value="despesa">-Aluguel</option>
                    <option value="receita">-Investimentos</option>
                    <option value="receita">+Proventos</option>
                    <option value="despesa">-Combustivel</option>
                    <option value="despesa">-Manutenções</option>
                    <option value="despesa">-Eletronicos</option>
                    <option value="despesa">-Outros Gastos</option>
                    <option value="despesa">+Outros</option>
                </select>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" class="form-control" id="descricao" name="descricao" required>
            </div>
            <div class="form-group">
                <label for="valor">Valor:</label>
                <input type="number" class="form-control" id="valor" name="valor" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    </div>
</div>
</div>




<!-- ---------------- -->
<br>
    <footer class="footer text-center">
    <p> &copy; 2023 Software De Finanças - Desenvolvido por 🤍<a href="mailto:diegorodriguesdantas02@gmail.com">Diego Dantas</a></p>
</footer>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
