<?php
// Incluir arquivo de configuração do banco de dados
require_once 'config.php';

// Resto do seu código aqui...
?>

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
<!-- --------- -->

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

    <div class="container">
        <h1 class="text-center">Transações</h1>

        <form class="mb-4" method="GET" action="transacoes.php">
            <div class="form-group">
                <input type="text" name="pesquisar" class="form-control" placeholder="Pesquisar por nome da transação">
            </div>
            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar se foi feita uma pesquisa
                if (isset($_GET['pesquisar'])) {
                    $pesquisa = $_GET['pesquisar'];
                    // Fazer a consulta considerando o termo de pesquisa
                    $consulta = "SELECT * FROM transacoes WHERE nome LIKE '%$pesquisa%' ORDER BY data DESC";
                } else {
                    // Consulta padrão sem pesquisa
                    $consulta = "SELECT * FROM transacoes ORDER BY data DESC";
                }

                // Executar a consulta no banco de dados
                $resultados = mysqli_query($conexao, $consulta);

                // Verificar se retornou algum resultado
                if (mysqli_num_rows($resultados) > 0) {
                    // Iterar sobre os resultados e exibi-los na tabela
                    while ($row = mysqli_fetch_assoc($resultados)) {
                        echo '<tr>';
                        echo '<th scope="row">' . $row['id'] . '</th>';
                        echo '<td>' . $row['nome'] . '</td>';
                        echo '<td>R$ ' . $row['valor'] . '</td>';
                        echo '<td>' . $row['data'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" class="text-center">Nenhuma transação encontrada.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Saldo Total -->
        <?php
        $saldo_total = 0;

        // Consulta para obter o saldo total
        $consulta_saldo = "SELECT SUM(valor) AS saldo_total FROM transacoes";
        $resultado_saldo = mysqli_query($conexao, $consulta_saldo);
        $row_saldo = mysqli_fetch_assoc($resultado_saldo);

        if ($row_saldo['saldo_total']) {
            $saldo_total = $row_saldo['saldo_total'];
        }
        ?>

        <p class="text-right">Saldo Total: R$ <?php echo $saldo_total; ?></p>
    </div>

    <!-- FIM do conteúdo-->

<!-- footer  -->

    <footer class="footer text-center">
        <p>&copy; 2023 Software De Finanças - Desenvolvido por 🤍<a href="mailto:diegorodriguesdantas02@gmail.com">Diego Dantas</a></p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
