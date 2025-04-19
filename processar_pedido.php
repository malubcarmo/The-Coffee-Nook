<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "the_coffee_nook";


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Recebe dados do formulário tradicional via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Dados do formulário
    $nome = htmlspecialchars($_POST['nome'] ?? '');
    $telefone = htmlspecialchars($_POST['telefone'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $endereco = htmlspecialchars($_POST['endereco'] ?? '');
    $observacoes = htmlspecialchars($_POST['obs'] ?? '');
    
    // Quantidade dos itens
    $qtd1 = isset($_POST['qtd1']) ? intval($_POST['qtd1']) : 0;
    $qtd2 = isset($_POST['qtd2']) ? intval($_POST['qtd2']) : 0;
    $qtd3 = isset($_POST['qtd3']) ? intval($_POST['qtd3']) : 0;

    // Preço dos itens
    $preco1 = 12.00;  // Cappuccino
    $preco2 = 9.00;   // Expresso (valor original)
    $preco2ComDesconto = 2.70; // Preço do Expresso com desconto
    $preco3 = 14.00;  // Café Gelado

    // Verifica se o cupom foi aplicado
    $usarCupom = isset($_POST['cupomAplicado']) && $_POST['cupomAplicado'] == '1';

    // Calcula o total dos itens
    $total1 = $preco1 * $qtd1;
    $total2 = ($usarCupom ? $preco2ComDesconto : $preco2) * $qtd2;
    $total3 = $preco3 * $qtd3;

    // Total geral
    $totalPedido = $total1 + $total2 + $total3;

    // Insere o pedido no banco
    $sql = "INSERT INTO pedidos (nome, telefone, email, endereco, observacoes, qtd1, qtd2, qtd3, preco1, preco2, preco3, total, cupom_usado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiiiddddi",
        $nome, $telefone, $email, $endereco, $observacoes,
        $qtd1, $qtd2, $qtd3,
        $preco1, $usarCupom ? $preco2ComDesconto : $preco2, $preco3,
        $totalPedido, $usarCupom ? 1 : 0
    );

    if ($stmt->execute()) {
        // Obtém o ID do pedido inserido
        $pedido_id = $stmt->insert_id;

        // Redireciona para a página de confirmação do pedido, passando o ID do pedido
        header("Location: confirmacao_pedido.php?pedido_id=" . $pedido_id);
        exit(); // Garante que o script não continue após o redirecionamento
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao salvar no banco: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Requisição inválida."]);
}
?>
