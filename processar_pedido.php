<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $observacoes = $_POST['obs'];

    // Aqui você pode salvar no banco, enviar e-mail, etc.

    // Exemplo de resposta simples:
    echo "<h1>Pedido recebido!</h1>";
    echo "<p>Nome: $nome</p>";
    echo "<p>Telefone: $telefone</p>";
    echo "<p>E-mail: $email</p>";
    echo "<p>Endereço: $endereco</p>";
    echo "<p>Observações: $observacoes</p>";
}
?>
