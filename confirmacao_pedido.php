<?php
// Conectar-se ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "the_coffee_nook";

// Conexão com o banco
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se o pedido_id foi passado na URL
$pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : 0;

if ($pedido_id > 0) {
    // Consultar os dados do pedido no banco de dados
    $sql = "SELECT nome, telefone, email, endereco, observacoes, totalPedido, cupom_usado FROM pedidos WHERE pedido_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Recuperar os dados do pedido
        $pedido = $result->fetch_assoc();

        // Consultar os itens do pedido (assumindo que existe uma tabela de itens)
        $sql_itens = "SELECT item, quantidade FROM pedido_itens WHERE pedido_id = ?";
        $stmt_itens = $conn->prepare($sql_itens);
        $stmt_itens->bind_param("i", $pedido_id);
        $stmt_itens->execute();
        $result_itens = $stmt_itens->get_result();

        $itens = [];
        while ($row = $result_itens->fetch_assoc()) {
            $itens[$row['item']] = $row['quantidade'];
        }

        $pedido['itens'] = $itens;
    } else {
        echo "Pedido não encontrado.";
        exit;
    }

    $stmt->close();
    $stmt_itens->close();
} else {
    echo "Pedido não encontrado.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação do Pedido</title>
</head>
<body>

<h1>Pedido Confirmado!</h1>

<p><strong>Nome:</strong> <?php echo htmlspecialchars($pedido['nome']); ?></p>
<p><strong>Telefone:</strong> <?php echo htmlspecialchars($pedido['telefone']); ?></p>
<p><strong>E-mail:</strong> <?php echo htmlspecialchars($pedido['email']); ?></p>
<p><strong>Endereço:</strong> <?php echo htmlspecialchars($pedido['endereco']); ?></p>
<p><strong>Observações:</strong> <?php echo htmlspecialchars($pedido['observacoes']); ?></p>

<h3>Itens do Pedido:</h3>
<?php
foreach ($pedido['itens'] as $item => $quantidade) {
    // Formatar o nome dos itens de forma mais amigável
    $itemNome = ucfirst(str_replace('_', ' ', $item)); // Substitui underscores por espaços e capitaliza a primeira letra
    echo "<p>$itemNome: $quantidade</p>";
}
?>

<h4>Total do Pedido: R$ <?php echo number_format($pedido['totalPedido'], 2, ',', '.'); ?></h4>

<p><a href="index.html" class="btn btn-primary">Voltar ao Início</a></p>

</body>
</html>
