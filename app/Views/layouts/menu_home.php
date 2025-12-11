 <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

<!-- ✅ Link do CSS externo responsável pelo estilo do menu -->
<link href="<?= base_url('assets/css/cssMenu.css') ?>" rel="stylesheet"> <!-- Estilo da área de Menu -->

<!-- ✅ Navbar estilizada com fundo nordestino -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm py-2"
     style="background: linear-gradient(90deg, #fff4db, #ffe8c2); border-bottom: 4px dashed #c58f3f;">
  <div class="container">

<a class="navbar-brand d-flex align-items-center" 
   href="<?= base_url('') ?>"
   style="font-family: 'Fredoka One', cursive; color: #963800; font-size: 1.5rem;">
  
  <img src="<?= base_url('assets/img_site/Logo_Elegante.png') ?>"
       alt="Logo"
       width="50" height="50"
       class="rounded-circle border border-2 border-danger shadow-sm me-2">

  <span style="line-height: 1.1;">Unirversos<br>Coletivo</span>
</a>

    <!-- ✅ Botão para menu colapsável no mobile -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ✅ Lista de itens do menu -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center text-center">

        <!-- 🔗 Projetos -->
        <li class="nav-item px-2">
          <a class="nav-link menu-link" href="#projetos">
            <i class="bi bi-lightbulb-fill text-warning"></i> Projetos
          </a>
        </li>

        <!-- 🔗 Histórico -->
        <li class="nav-item px-2">
          <a class="nav-link menu-link" href="#historico">
            <i class="bi bi-geo-alt-fill text-danger"></i> Onde Já Estivemos
          </a>
        </li>

        <!-- 🔗 Ecossistema -->
        <li class="nav-item px-2">
          <a class="nav-link menu-link" href="#ecossistema">
            <i class="bi bi-diagram-3-fill text-success"></i> Ecossistema
          </a>
        </li>

        <!-- 🔗 Quem Somos -->
        <li class="nav-item px-2">
          <a class="nav-link menu-link" href="<?= base_url('quemsomos') ?>">
            <i class="bi bi-people-fill text-info"></i> Quem Somos
          </a>
        </li>

        <!-- 🔗 Galeria com submenu -->
        <li class="nav-item dropdown px-2">
          <a class="nav-link dropdown-toggle menu-link" href="#" id="galeriaDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-images text-warning"></i> Galeria
          </a>
          <ul class="dropdown-menu" aria-labelledby="galeriaDropdown">
            <li><a class="dropdown-item" href="<?= base_url('fotos') ?>">📷 Galeria de Fotos</a></li>
            <li><a class="dropdown-item" href="<?= base_url('videos') ?>">🎬 Galeria de Vídeos</a></li>
          </ul>
        </li>

        <!-- 🔗 Contato (abre modal) -->
        <li class="nav-item px-2">
          <a class="nav-link menu-link" href="#" data-bs-toggle="modal" data-bs-target="#modalContato">
            <i class="bi bi-envelope-fill text-danger"></i> Contato
          </a>
        </li>

        <!-- 🔗 Instagram externo -->
        <li class="nav-item px-2">
          <a class="nav-link menu-link" href="https://www.instagram.com/unirversos_coletivo/" target="_blank">
            <i class="bi bi-instagram text-danger"></i> Instagram
          </a>
        </li>

   <!-- 🔗 Portfólio 
<li class="nav-item px-2">
  <a class="nav-link menu-link" href="<?= base_url('portifolio') ?>">
    <i class="bi bi-briefcase-fill text-success"></i> Portfólio
  </a>
</li>
-->

      </ul>
    </div>
  </div>
</nav>

<!-- ✅ MODAL DE CONTATO -->
<div class="modal fade" id="modalContato" tabindex="-1" aria-labelledby="modalContatoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-light shadow-lg rounded-4">

      <!-- 🔴 Cabeçalho do modal -->
      <div class="modal-header bg-danger text-white rounded-top-4">
        <h5 class="modal-title fw-bold text-white" id="modalContatoLabel"
            style="font-family: 'Fredericka the Great', cursive; font-size: 1.8rem;">
          🌾 Entre em contato com o Unirversos Coletivo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      

      <!-- ✅ Script para exibir o loader durante envio -->
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const form = document.querySelector("#modalContato form");
          const btnEnviar = document.getElementById("btnEnviarMensagem");
          const loader = document.getElementById("loaderEnvio");

          form.addEventListener("submit", function () {
            btnEnviar.disabled = true;
            btnEnviar.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Enviando...';
            loader.classList.remove("d-none");
          });
        });
      </script>

     <!-- ✅ Formulário de contato -->
<form action="<?= base_url('contato') ?>" method="post" class="form-nordeste" autocomplete="on">
  <?= csrf_field() ?>
  <input type="hidden" name="Assunto" value="Universos Coletivo – Contato de visitante do site">

  <!-- Nome -->
  <div class="mb-3">
    <label for="nome" class="form-label">🧑 Nome completo</label>
    <input type="text" class="form-control" id="nome" name="Nome" required autocomplete="name">
  </div>

  <!-- Email -->
  <div class="mb-3">
    <label for="email" class="form-label">📧 Seu e-mail</label>
    <input type="email" class="form-control" id="email" name="Email" required autocomplete="email">
  </div>

  <!-- Mensagem -->
  <div class="mb-3">
    <label for="mensagem" class="form-label">🎭 Mensagem ou projeto cultural</label>
    <textarea class="form-control" id="mensagem" name="Mensagem" rows="5" required autocomplete="off"></textarea>
  </div>

  <!-- Botões -->
  <div class="d-flex justify-content-between mt-4">
    <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
    <button id="btnEnviarMensagem" type="submit" class="btn btn-nordeste">🎤 Quero me apresentar!</button>
  </div>

  <!-- Loader de envio (aparece após clicar em enviar) -->
  <div id="loaderEnvio" class="d-none"
       style="position: absolute;
              top: 50%; left: 50%;
              transform: translate(-50%, -50%);
              background: rgba(255, 255, 255, 0.85);
              padding: 20px 30px;
              border-radius: 12px;
              box-shadow: 0 0 10px rgba(0,0,0,0.15);
              z-index: 9999;
              text-align: center;
              font-family: 'Amatic SC', cursive;
              font-size: 1.2rem;
              color: #b84f00;">
    <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
      <span class="visually-hidden">Enviando...</span>
    </div>
    <p class="mt-2 mb-0">Enviando sua mensagem... Só um tiquinho! 🌾</p>
  </div>
</form>

    </div>
  </div>
</div>

<!-- ✅ Scripts Bootstrap (collapse, dropdown, modal, etc) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

