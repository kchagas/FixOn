<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<div class="container-fluid">
<a class="navbar-brand" href="#">Sistema</a>
<div class="d-flex">
<span class="me-3">Olá, <?= session()->get('user_name') ?></span>
<a href="<?= site_url('logout') ?>" class="btn btn-outline-secondary">Sair</a>
</div>
</div>
</nav>


<div class="container mt-4">
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
<h3>Dashboard</h3>
<p>Bem-vindo ao painel.</p>
</div>
</div>
</div>
</div>
</div>


</body>
</html>