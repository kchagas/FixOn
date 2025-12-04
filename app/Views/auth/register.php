<!doctype html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registrar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card">
<div class="card-body">
<h4>Registrar nova conta</h4>
<form action="<?= site_url('register') ?>" method="post">
<?= csrf_field() ?>
<div class="mb-3">
<label>Nome</label>
<input type="text" name="nome" class="form-control" value="<?= set_value('nome') ?>">
</div>
<div class="mb-3">
<label>E-mail</label>
<input type="email" name="email" class="form-control" value="<?= set_value('email') ?>">
</div>
<div class="mb-3">
<label>Senha</label>
<input type="password" name="senha" class="form-control">
</div>
<div class="mb-3">
<label>Role (apenas teste)</label>
<select name="role" class="form-select">
<option value="user">Usuário</option>
<option value="admin">Administrador</option>
</select>
</div>
<button class="btn btn-success">Criar</button>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>