<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3>Saída de Estoque</h3>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<form action="<?= site_url('movimentacoes/salvarSaida') ?>" method="post" class="row g-3">

    <div class="col-md-6">
        <label class="form-label">Peça</label>
        <select name="peca_id" class="form-control" required>
            <?php foreach($pecas as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nome'] ?> (<?= $p['sku'] ?>) – Estoque: <?= $p['estoque_atual'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Quantidade</label>
        <input type="number" name="quantidade" class="form-control" required min="1">
    </div>

    <div class="col-md-12">
        <label class="form-label">Motivo</label>
        <input type="text" name="motivo" class="form-control">
    </div>

    <div class="col-12">
        <button class="btn btn-danger">Registrar Saída</button>
    </div>

</form>

<?= $this->endSection() ?>
