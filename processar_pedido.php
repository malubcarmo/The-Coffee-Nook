<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "the_coffee_nook";

// Ativa exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Verificar se a requisição é POST e se o conteúdo é JSON
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Lê o JSON bruto do corpo da requisição
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data) {
        echo json_encode(["success" => false, "message" => "JSON inválido"]);
        error_log("Erro: JSON inválido ou mal formado.");
        exit;
    }

    // Extrai os dados
    $nome = htmlspecialchars($data['nome'] ?? '');
    $telefone = htmlspecialchars($data['telefone'] ?? '');
    $email = htmlspecialchars($data['email'] ?? '');
    $endereco = htmlspecialchars($data['endereco'] ?? '');
    $observacoes = htmlspecialchars($data['obs'] ?? '');

    $qtd1 = intval($data['qtd1'] ?? 0);
    $qtd2 = intval($data['qtd2'] ?? 0);
    $qtd3 = intval($data['qtd3'] ?? 0);
    $usarCupom = isset($data['cupomAplicado']) && in_array($data['cupomAplicado'], [0, 1]) ? (bool)$data['cupomAplicado'] : false;

    // Preços
    $preco1 = 12.00;
    $preco2 = 9.00;
    $preco2ComDesconto = 2.70;
    $preco3 = 14.00;

    // Cálculo
    $total1 = $preco1 * $qtd1;
    $total2 = ($usarCupom ? $preco2ComDesconto : $preco2) * $qtd2;
    $total3 = $preco3 * $qtd3;
    $totalPedido = $total1 + $total2 + $total3;

    // Insere no banco
    $sql = "INSERT INTO pedidos (nome, telefone, email, endereco, observacoes, qtd1, qtd2, qtd3, preco1, preco2, preco3, total, cupom_usado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Erro na preparação do SQL: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Erro na preparação da consulta"]);
        exit;
    }

    $stmt->bind_param("ssssssiiiddddi",
        $nome, $telefone, $email, $endereco, $observacoes,
        $qtd1, $qtd2, $qtd3,
        $preco1, $usarCupom ? $preco2ComDesconto : $preco2, $preco3,
        $totalPedido, $usarCupom ? 1 : 0
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pedido salvo com sucesso!", "pedido_id" => $stmt->insert_id]);
    } else {
        error_log("Erro ao executar a consulta: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Erro ao salvar no banco: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    error_log("Requisição inválida ou tipo de conteúdo incorreto.");
    echo json_encode(["success" => false, "message" => "Requisição inválida ou tipo de conteúdo incorreto."]);
}
?>
