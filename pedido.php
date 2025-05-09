<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pedido | The Coffee Nook</title>
  <link rel="icon" type="image/png" href="img/logos/logo2.png"> <!-- Ícone na aba -->

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
  <header class="py-3 border-bottom bg-white bg-opacity-75 sticky-top shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
      <h1 class="m-0 fw-bold text-brown fs-4">The Coffee Nook</h1>
      <nav>
        <a class="nav-link fw-semibold text-brown" href="index.html">Voltar ao Início</a>
      </nav>
    </div>
  </header>

  <main class="container py-5">
    <h2 class="text-center mb-5 fw-bold text-coffee">Monte seu pedido</h2>

        <!-- Produtos -->
    <div class="mb-5">
        <!-- Item 1: Cappuccino -->
    <div class="card mb-4 p-3 d-flex flex-row align-items-center justify-content-between">
        <!-- Lado esquerdo: imagem + nome -->
        <div class="d-flex align-items-center gap-3">
            <img src="img/itens/item1.webp" alt="Cappuccino" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
            <div>
                <h5 class="fw-semibold text-coffee mb-1">Cappuccino</h5>
                <p class="mb-0">R$ <span id="preco1">12,00</span></p>
            </div>
        </div>

        <!-- Lado direito: quantidade + total -->
        <div class="text-end">
            <input type="number" class="form-control form-control-sm w-auto d-inline-block mb-1" id="qtd1" min="0" max="10" value="0">
            <p class="text-secondary mb-0">Total: R$ <span id="total1">0,00</span></p>
            <p class="text-warning mb-0">Limitado a 10 unidades.</p> <!-- Aviso de limite -->
        </div>
    </div>
    <!-- Item 2: Expresso -->
    <div class="card mb-4 p-3 d-flex flex-row align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="img/itens/item2.webp" alt="Expresso" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
            <div>
                <h5 class="fw-semibold text-coffee mb-1">Expresso</h5>
                <p class="mb-0">R$ <span id="preco2">9,00</span></p>
            </div>
        </div>
        <div class="text-end">
            <input type="number" class="form-control form-control-sm w-auto d-inline-block mb-1" id="qtd2" min="0" max="10" value="0">
            <p class="text-secondary mb-0">Total: R$ <span id="total2">0,00</span></p>
            <p class="text-warning mb-0">Limitado a 10 unidades.</p> <!-- Aviso de limite -->
        </div>
    </div>
    <!-- Item 3: Café Gelado -->
    <div class="card mb-4 p-3 d-flex flex-row align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="img/itens/item3.webp" alt="Café Gelado" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
            <div>
                <h5 class="fw-semibold text-coffee mb-1">Café Gelado</h5>
                <p class="mb-0">R$ <span id="preco3">14,00</span></p>
            </div>
        </div>
        <div class="text-end">
            <input type="number" class="form-control form-control-sm w-auto d-inline-block mb-1" id="qtd3" min="0" max="10" value="0">
            <p class="text-secondary mb-0">Total: R$ <span id="total3">0,00</span></p>
            <p class="text-warning mb-0">Limitado a 10 unidades.</p> <!-- Aviso de limite -->
        </div>
    </div>   
        <!-- Total -->
          <div class="mb-5">
            <h4 class="text-end text-coffee">Total: R$ <span id="total">0,00</span></h4>
          </div>
        <!-- Cupom -->
          <div class="mb-3 text-end">
            <label for="cupom" class="form-label">Cupom de desconto:</label>
            <input type="text" id="cupom" class="form-control d-inline-block w-auto" placeholder="Digite o cupom">
            <button type="button" class="btn btn-outline-success ms-2" onclick="aplicarCupom()">Aplicar</button>
          </div>
        <!-- Valor do desconto -->
        <div class="text-end text-success">
          <span id="desconto"></span>
        </div>
        <div id="alerta-cupom" class="alert alert-success alert-dismissible fade show mt-3 d-none" role="alert">
          <strong>Cupom aplicado com sucesso!</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
        
    <!-- Formulário -->
    <form method="POST" action="processar_pedido.php">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="nome" class="form-label">Nome</label>
      <input type="text" class="form-control" id="nome" name="nome" required />
    </div>
    <div class="col-md-6 mb-3">
      <label for="telefone" class="form-label">Telefone</label>
      <input type="tel" class="form-control" id="telefone" name="telefone" required />
    </div>
    <div class="col-md-6 mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" class="form-control" id="email" name="email" required />
    </div>
    <div class="col-md-6 mb-3">
      <label for="endereco" class="form-label">Endereço</label>
      <input type="text" class="form-control" id="endereco" name="endereco" required />
    </div>
    <div class="col-12 mb-4">
      <label for="obs" class="form-label">Observações</label>
      <textarea class="form-control" id="obs" name="obs" rows="3"></textarea>
    </div>

  <!-- Campos ocultos com as quantidades dos produtos - IDs corrigidos -->
    <input type="hidden" id="qtd1_hidden" name="qtd1" value="0">
    <input type="hidden" id="qtd2_hidden" name="qtd2" value="0">
    <input type="hidden" id="qtd3_hidden" name="qtd3" value="0">
    <input type="hidden" id="cupomAplicado" name="cupomAplicado" value="0">
    <div class="text-end">
      <button type="submit" class="btn btn-custom px-4 fw-bold">Finalizar Pedido</button>
    </div>
  </div>
</form>
  </main>
  
  <footer class="text-white py-3" style="background-color: #4a2314;">
    <div class="container text-center">
      <p class="m-0">&copy; 2025 The Coffee Nook. Todos os direitos reservados.</p>
    </div>
  </footer>

  <!-- Modal de Sucesso -->
  <div class="modal fade" id="modalSucesso" tabindex="-1" aria-labelledby="modalSucessoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modalSucessoLabel">
            <i class="bi bi-check-circle-fill me-2"></i>Pedido Realizado com Sucesso!
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
          <p class="mt-3 fs-5">Obrigado pela sua compra!</p>
          <p>Seu pedido foi recebido e será preparado com todo o carinho.</p>
          <p class="text-muted">Você será redirecionado para a página inicial em <span id="contador">3</span> segundos...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script de calculo de quantidade, desconto e valor total -->
  <script>
  const form = document.querySelector("form");

  const qtd1 = document.getElementById('qtd1');
  const qtd2 = document.getElementById('qtd2');
  const qtd3 = document.getElementById('qtd3');
  const total = document.getElementById('total');

  const total1 = document.getElementById('total1');
  const total2 = document.getElementById('total2');
  const total3 = document.getElementById('total3');
  const descontoText = document.getElementById('desconto');
  const alerta = document.getElementById('alerta-cupom');

  let descontoCupom = 0;

  function calcularTotal() {
    const preco1 = 12.00;
    const preco2 = 9.00;
    const preco3 = 14.00;

    const qtdCappuccino = parseInt(qtd1.value || 0);
    const qtdExpresso = parseInt(qtd2.value || 0);
    const qtdCafeGelado = parseInt(qtd3.value || 0);

    const t1 = preco1 * qtdCappuccino;
    const t2SemDesconto = preco2 * qtdExpresso;
    const t3 = preco3 * qtdCafeGelado;

    const descontoAplicado = descontoCupom > 0 ? preco2 * descontoCupom * qtdExpresso : 0;
    const t2 = t2SemDesconto - descontoAplicado;

    total1.textContent = t1.toFixed(2);
    total2.textContent = t2.toFixed(2);
    total3.textContent = t3.toFixed(2);

    const totalPedido = (t1 + t2 + t3).toFixed(2);
    total.textContent = totalPedido;

    descontoText.textContent = descontoAplicado > 0
      ? `Você economizou R$ ${descontoAplicado.toFixed(2)} com o cupom "NOOK"!`
      : '';

    // Atualiza campos ocultos com os valores corretos
    document.getElementById('qtd1_hidden').value = qtdCappuccino;
    document.getElementById('qtd2_hidden').value = qtdExpresso;
    document.getElementById('qtd3_hidden').value = qtdCafeGelado;
  }

  function aplicarCupom() {
    const valorCupom = document.getElementById('cupom').value.trim().toUpperCase();

    if (valorCupom === "NOOK") {
      descontoCupom = 0.7;
      alerta.classList.remove("d-none");
      alerta.classList.add("show");
      document.getElementById('cupomAplicado').value = '1'; // Marca o cupom como aplicado

      setTimeout(() => {
        alerta.classList.add("d-none");
        alerta.classList.remove("show");
      }, 5000);
    } else {
      descontoCupom = 0;
      descontoText.textContent = 'Cupom inválido.';
      alerta.classList.add("d-none");
      document.getElementById('cupomAplicado').value = '0'; // Marca como não aplicado
    }

    calcularTotal();
  }

  qtd1.addEventListener('input', calcularTotal);
  qtd2.addEventListener('input', calcularTotal);
  qtd3.addEventListener('input', calcularTotal);

  calcularTotal(); // Inicializa com valores zerados

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // impede o envio tradicional do formulário

    const nome = document.getElementById('nome').value;
    const telefone = document.getElementById('telefone').value;
    const email = document.getElementById('email').value;
    const endereco = document.getElementById('endereco').value;
    const obs = document.getElementById('obs').value;

    const pedido = {
      nome,
      telefone,
      email,
      endereco,
      obs,
      qtd1: document.getElementById('qtd1').value,
      qtd2: document.getElementById('qtd2').value,
      qtd3: document.getElementById('qtd3').value,
      cupomAplicado: descontoCupom > 0 ? 1 : 0,
      total: total.textContent
    };

    console.log("Enviando pedido:", pedido); // Para debug

    fetch('processar_pedido.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(pedido),
    })
    .then(response => {
      console.log("Status da resposta:", response.status); // Debug
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(text => {
      console.log("Resposta bruta do servidor:", text); // Debug
      try {
        const data = JSON.parse(text);
        
        if (data.success) {
          // Mostrar modal de sucesso
          const modalSucesso = new bootstrap.Modal(document.getElementById('modalSucesso'));
          modalSucesso.show();
          
          // Iniciar contagem regressiva
          let contador = 3;
          const contadorElement = document.getElementById('contador');
          
          const interval = setInterval(() => {
            contador--;
            contadorElement.textContent = contador;
            
            if (contador <= 0) {
              clearInterval(interval);
              window.location.href = 'index.html';
            }
          }, 1000);
          
          // Resetar o formulário
          form.reset();
          calcularTotal();
          descontoText.textContent = '';
          document.getElementById('cupomAplicado').value = '0';
          
          // Adicionar event listener para o botão de fechar do modal
          document.querySelector('#modalSucesso .btn-secondary').addEventListener('click', () => {
            clearInterval(interval);
            window.location.href = 'index.html';
          });
          
        } else {
          alert('Erro ao processar o pedido: ' + (data.message || 'Erro desconhecido'));
          console.error(data.message);
        }
      } catch (e) {
        console.error('Erro ao fazer parse do JSON:', e);
        console.error('Texto recebido:', text);
        alert('Erro ao processar a resposta do servidor. Verifique o console para mais detalhes.');
      }
    })
    .catch(error => {
      console.error('Erro:', error);
      alert('Erro ao processar o pedido: ' + error.message);
    });
  });
</script>
</body>
</html>