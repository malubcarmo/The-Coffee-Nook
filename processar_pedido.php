<?php
$servername = 'localhost';
$dbname = 'the_coffee_nook';
$username = 'root';
$password = '';

// Ativa exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia o buffer de saída
ob_start();

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Falha na conexão: " . $conn->connect_error]));
}

// Cabeçalhos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Verificar se a requisição é POST e se o conteúdo é JSON
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Lê o JSON bruto do corpo da requisição
    $json = file_get_contents("php://input");
    // Grava no log (apenas para testes)
    file_put_contents("log.txt", $json);
    
    // Decodifica o JSON
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

    // Preço final com ou sem desconto para o produto 2
    $preco2Final = $usarCupom ? $preco2ComDesconto : $preco2;

    // Cálculo
    $total1 = $preco1 * $qtd1;
    $total2 = $preco2Final * $qtd2;
    $total3 = $preco3 * $qtd3;
    $totalPedido = $total1 + $total2 + $total3;

    // Prepara o SQL
    $sql = "INSERT INTO pedidos (nome, telefone, email, endereco, observacoes, qtd1, qtd2, qtd3, preco1, preco2, preco3, total, cupom_usado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara a declaração
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Erro na preparação do SQL: " . $conn->error);
        echo json_encode(["success" => false, "message" => "Erro na preparação da consulta"]);
        exit;
    }

    // Passa os parâmetros por referência
    $cupomUsado = $usarCupom ? 1 : 0; // Atribuímos 1 ou 0 para o cupom

    // Passa os parâmetros para o bind_param
    $stmt->bind_param("sssssiiiddddi",
        $nome, $telefone, $email, $endereco, $observacoes,
        $qtd1, $qtd2, $qtd3,
        $preco1, $preco2Final, $preco3, 
        $totalPedido, $cupomUsado
    );

    // Executa a consulta
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

// Captura todos os erros do buffer
file_put_contents('erro_log_php.txt', ob_get_clean());
?>
