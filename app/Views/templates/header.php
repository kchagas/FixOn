<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title ?? 'FixOn SaaS') ?></title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- DataTables + Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">






<style>
/* ====== VARIÁVEIS DE TEMA (light por padrão) ====== */
:root{
  --bg: #f8f9fa;
  --navbar-bg: #ffffff;
  --navbar-text: #212529;
  --primary: #0d6efd;
  --sidebar-bg: #0d6efd;
  --sidebar-text: #ffffff;
  --content-text: #212529;
  --card-bg: #ffffff;
  --muted: #6c757d;
  --shadow: rgba(0,0,0,0.08);
}

/* ====== TEMA ESCURO ====== */
.dark-theme {
  --bg: #121212;
  --navbar-bg: #0f1720;
  --navbar-text: #e6eef8;
  --primary: #2b7cff;
  --sidebar-bg: #0b1220;
  --sidebar-text: #eaf4ff;
  --content-text: #e6eef8;
  --card-bg: #0f1724;
  --muted: #98a0ab;
  --shadow: rgba(0,0,0,0.6);
}

/* ====== RESET & BASE ====== */
body{
  background: var(--bg);
  color: var(--content-text);
  transition: background .25s ease, color .25s ease;
  min-height: 100vh;
}

/* ====== NAVBAR ====== */
.navbar {
  background: var(--navbar-bg) !important;
  color: var(--navbar-text) !important;
}
.navbar .navbar-brand, .navbar .nav-link, .navbar .btn {
  color: var(--navbar-text) !important;
}

/* ====== SIDEBAR ====== */
#sidebar {
  width: 250px;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  background: var(--sidebar-bg);
  color: var(--sidebar-text);
  padding-top: 70px;
  transition: all .3s ease;
  overflow-y: auto;
  box-shadow: 2px 0 6px var(--shadow);
}
#sidebar a { color: var(--sidebar-text); text-decoration: none; }
#sidebar .menu-item { padding: 12px 20px; cursor: pointer; display:flex; gap:10px; align-items:center; }
#sidebar .menu-item:hover { background: rgba(255,255,255,0.06); }

/* Submenu */
.submenu { background: rgba(0,0,0,0.06); display:none; padding-left:45px; animation: fadeIn .25s ease; }
.submenu a { display:block; padding:8px 0; color:var(--sidebar-text); }

/* ====== CONTEÚDO ====== */
#content { margin-left:250px; transition: all .3s ease; padding-top: 1rem; padding-bottom:2rem; }

/* ====== CARDS ====== */
.card {
  background: var(--card-bg);
  color: var(--content-text);
  border: none;
  box-shadow: 0 2px 8px var(--shadow);
}

/* ====== COLAPSAR SIDEBAR ====== */
.sidebar-collapsed { width: 70px !important; }
.content-expanded { margin-left: 70px !important; }
.hide-text { display: none !important; }

/* ====== ANIMAÇÃO SUBMENU ====== */
@keyframes fadeIn { from{opacity:0; transform: translateY(-4px)} to{opacity:1; transform:none} }

/* ====== Ajutes responsivos ====== */
@media (max-width: 768px){
  #sidebar { transform: translateX(-100%); position: fixed; z-index:1040; }
  #sidebar.show { transform: translateX(0); }
  #content { margin-left: 0 !important; }
  .sidebar-backdrop { display:none; }
}
</style>
</head>

<body>
    

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
  <div class="container-fluid">

    <!-- TOGGLE SIDEBAR (desktop) -->
    <button class="btn btn-outline-primary me-2" id="toggleSidebar" title="Abrir/Fechar menu">
      <i class="bi bi-list"></i>
    </button>

    <a class="navbar-brand fw-bold" href="<?= site_url('dashboard') ?>">FixOn SaaS</a>

    <div class="ms-auto d-flex align-items-center">
      <!-- TEMA TOGGLE -->
      <div class="me-3 d-flex align-items-center">
        <button id="themeToggle" class="btn btn-outline-secondary" title="Alternar tema">
          <i id="themeIcon" class="bi bi-brightness-high"></i>
        </button>
      </div>

      <span class="me-3">Olá, <?= session()->get('user_name') ?></span>
      <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
    </div>
  </div>
</nav>
