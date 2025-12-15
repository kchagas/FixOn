<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4 d-flex justify-content-between">
    Categorias
    <a href="<?= site_url('categorias/cadastrar') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nova Categoria
    </a>
</h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


<table class="table table-dark table-striped align-middle">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Status</th>
            <th width="160">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= esc($c['nome']) ?></td>
                <td><?= esc($c['descricao']) ?></td>
                <td>
                    <?= $c['ativo']
                        ? '<span class="badge bg-success">Ativa</span>'
                        : '<span class="badge bg-secondary">Inativa</span>' ?>
                </td>
                <td>
                   <a href="<?= site_url('categorias/editar/'.$c['id']) ?>" class="btn btn-sm btn-primary">
    <i class="bi bi-pencil"></i>
</a>

<?php if ($c['ativo']): ?>
    <a href="<?= site_url('categorias/desativar/'.$c['id']) ?>"
       class="btn btn-sm btn-warning"
       title="Desativar">
        <i class="bi bi-eye-slash"></i>
    </a>
<?php else: ?>
    <a href="<?= site_url('categorias/ativar/'.$c['id']) ?>"
       class="btn btn-sm btn-success"
       title="Ativar">
        <i class="bi bi-eye"></i>
    </a>
<?php endif; ?>


                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
