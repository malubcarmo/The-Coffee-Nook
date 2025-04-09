<?php
// Dados de conexão com o banco de dados
$servername = "localhost"; // Geralmente é localhost
$username = "seu_usuario_db"; // Seu nome de usuário do banco de dados
$password = "sua_senha_db"; // Sua senha do banco de dados
$dbname = "nome_do_seu_banco"; // O nome do seu banco de dados
$table_name = "pedidos"; // O nome da tabela onde você vai salvar os pedidos

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
    $itens_pedidos = $_POST['itens'] ?? [];
    $quantidades = [];
    if (isset($_POST['quantidade_cafe_espresso'])) $quantidades['Café Espresso'] = filter_input(INPUT_POST, 'quantidade_cafe_espresso', FILTER_SANITIZE_NUMBER_INT);
    if (isset($_POST['quantidade_cappuccino'])) $quantidades['Cappuccino'] = filter_input(INPUT_POST, 'quantidade_cappuccino', FILTER_SANITIZE_NUMBER_INT);
    if (isset($_POST['quantidade_cafe_gelado'])) $quantidades['Café Gelado'] = filter_input(INPUT_POST, 'quantidade_café_gelado', FILTER_SANITIZE_NUMBER_INT);
    $observacoes = filter_input(INPUT_POST, 'observacoes', FILTER_SANITIZE_STRING);
    $tipo_pedido = filter_input(INPUT_POST, 'tipo_pedido', FILTER_SANITIZE_STRING);
    $endereco_entrega = filter_input(INPUT_POST, 'endereco_entrega', FILTER_SANITIZE_STRING);

    // Montar a string dos itens do pedido e quantidades
    $pedido_detalhes = "";
    if (!empty($itens_pedidos)) {
        foreach ($itens_pedidos as $item) {
            if (isset($quantidades[$item]) && $quantidades[$item] > 0) {
                $pedido_detalhes .= $item . ": " . $quantidades[$item] . ", ";
            }
        }
        $pedido_detalhes = rtrim($pedido_detalhes, ", "); // Remover a última vírgula e espaço
    } else {
        $pedido_detalhes = "Nenhum item selecionado.";
    }

    // Preparar a consulta SQL para inserir os dados
    $sql = "INSERT INTO $table_name (nome, email, telefone, itens, observacoes, tipo_pedido, endereco_entrega, data_pedido)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    // Preparar a declaração
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular os parâmetros
        $stmt->bind_param("sssssss", $nome, $email, $telefone, $pedido_detalhes, $observacoes, $tipo_pedido, $endereco_entrega);

        // Executar a declaração
        if ($stmt->execute()) {
            echo "<p style='color: green; text-align: center; margin-top: 20px;'>Seu pedido foi registrado com sucesso!</p>";
        } else {
            echo "<p style='color: red; text-align: center; margin-top: 20px;'>Houve um erro ao registrar seu pedido: " . $stmt->error . "</p>";
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        echo "<p style='color: red; text-align: center; margin-top: 20px;'>Erro na preparação da consulta: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: red; text-align: center; margin-top: 20px;'>Acesso inválido.</p>";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>