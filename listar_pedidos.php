<?php
$host = 'localhost';
$dbname = "the_coffee_nook";
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Excluir pedido
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    // Usar prepared statement para excluir o pedido de forma segura
    $stmt = $conn->prepare("DELETE FROM pedidos WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: listar_pedidos.php");
        exit;
    } else {
        echo "Erro ao excluir o pedido.";
    }
    $stmt->close();
}

// Listar pedidos
$result = $conn->query("SELECT * FROM pedidos ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Pedidos Realizados</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($pedido = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= htmlspecialchars($pedido['nome']) ?></td>
                <td><?= htmlspecialchars($pedido['telefone']) ?></td>
                <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                <td>
                    <a href="detalhar_pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-info">Detalhar</a>
                    <a href="?excluir=<?= $pedido['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este pedido?')">Excluir</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</body>
</html>
