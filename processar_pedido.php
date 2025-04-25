<?php
// Desativa exibição de erros para não interferir no JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Inicia o buffer de saída
ob_start();

// Obtém o diretório atual
$currentDir = __DIR__ . DIRECTORY_SEPARATOR;

// Configurações do banco de dados
$servername = 'localhost';
$dbname = 'the_coffee_nook';
$username = 'root';
$password = '';

// Define cabeçalhos antes de qualquer saída
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

try {
    // Conexão
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }

    // Verificar se a requisição é POST e se o conteúdo é JSON
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        // Lê o JSON bruto do corpo da requisição
        $json = file_get_contents("php://input");
        
        // Log para debug (usando caminho completo)
        $logFile = $currentDir . 'log.txt';
        if (is_writable($currentDir)) {
            // Tenta criar o arquivo se não existir
            if (!file_exists($logFile)) {
                touch($logFile);
                chmod($logFile, 0666); // Dá permissões de escrita
            }
            
            if (is_writable($logFile)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Recebido: " . $json . "\n", FILE_APPEND);
            }
        }
        
        // Decodifica o JSON
        $data = json_decode($json, true);

        if (!$data) {
            throw new Exception("JSON inválido");
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
            throw new Exception("Erro na preparação da consulta: " . $conn->error);
        }

        // Passa os parâmetros por referência
        $cupomUsado = $usarCupom ? 1 : 0;

        // Passa os parâmetros para o bind_param
        $stmt->bind_param("sssssiiiddddi",
            $nome, $telefone, $email, $endereco, $observacoes,
            $qtd1, $qtd2, $qtd3,
            $preco1, $preco2Final, $preco3, 
            $totalPedido, $cupomUsado
        );

        // Executa a consulta
        if ($stmt->execute()) {
            ob_end_clean(); // Limpa o buffer antes de retornar JSON
            echo json_encode(["success" => true, "message" => "Pedido salvo com sucesso!", "pedido_id" => $stmt->insert_id]);
        } else {
            throw new Exception("Erro ao salvar no banco: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();

    } else {
        throw new Exception("Requisição inválida ou tipo de conteúdo incorreto.");
    }

} catch (Exception $e) {
    // Limpa o buffer
    ob_end_clean();
    
    // Retorna JSON de erro
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

// Log final de erros
$errorLogFile = $currentDir . 'erro_log_php.txt';
if (is_writable($currentDir)) {
    file_put_contents($errorLogFile, ob_get_clean());
}

// Certifica que nada mais é enviado após o JSON
exit();
?>