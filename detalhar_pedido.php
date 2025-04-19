<?php
$host = 'localhost';
$dbname = "the_coffee_nook";
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$id = intval($_GET['id']);

// Usar prepared statement para a consulta
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Pedido não encontrado.";
    exit;
}

$pedido = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Detalhes do Pedido #<?= $pedido['id'] ?></h1>
    <p><strong>Nome:</strong> <?= htmlspecialchars($pedido['nome']) ?></p>
    <p><strong>Telefone:</strong> <?= htmlspecialchars($pedido['telefone']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
    <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['endereco']) ?></p>
    <p><strong>Observações:</strong> <?= nl2br(htmlspecialchars($pedido['observacoes'])) ?></p>
    <hr>
    <p><strong>Cappuccino:</strong> <?= $pedido['qtd1'] ?> x R$ 12,00</p>
    
    <?php
    // Verificando se o cupom foi usado para o Expresso
    $precoExpresso = ($pedido['cupom_usado'] == 1) ? 'R$ 2,70' : 'R$ 9,00';
    ?>
    
    <p><strong>Expresso:</strong> <?= $pedido['qtd2'] ?> x <?= $precoExpresso ?></p>
    <p><strong>Café Gelado:</strong> <?= $pedido['qtd3'] ?> x R$ 14,00</p>
    <hr>
    <h4><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></h4>
    <a href="listar_pedidos.php" class="btn btn-secondary mt-3">Voltar</a>
</body>
</html>
