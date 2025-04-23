// Script existente - não alterado
document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
  const navbarToggler = document.querySelector('.navbar-toggler');
  const navbarCollapse = document.getElementById('navbarNav');

  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      // Fecha o menu (modo mobile)
      if (window.innerWidth < 768 && navbarCollapse.classList.contains('show')) {
        new bootstrap.Collapse(navbarCollapse).hide();
      }
    });
  });
});


// Novo script: Envia o formulário de contato, limpa os campos e exibe mensagem temporária
document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contactForm");
  const msgSucesso = document.getElementById("mensagem-sucesso");

  if (contactForm && msgSucesso) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Aqui poderia entrar lógica de envio via fetch, se necessário.

      contactForm.reset(); // Limpa o formulário
      msgSucesso.classList.remove("d-none");

      setTimeout(() => {
        msgSucesso.classList.add("d-none");
      }, 5000); // Oculta após 5 segundos
    });
  }
});
