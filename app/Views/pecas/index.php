<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Peças Cadastradas</h3>

<a href="<?= site_url('pecas/cadastrar') ?>" class="btn btn-success mb-3">
    + Nova Peça
</a>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-dark table-hover mb-0">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nome</th>
                    <th>Estoque</th>
                    <th>Mínimo</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($pecas as $p): ?>

                    <?php
                        $status = $p['estoque_atual'] <= $p['estoque_minimo']
                            ? "<span class='badge bg-danger'>Crítico</span>"
                            : "<span class='badge bg-success'>OK</span>";
                    ?>

                    <tr>
                        <td><?= esc($p['sku']) ?></td>
                        <td><?= esc($p['nome']) ?></td>
                        <td><?= esc($p['estoque_atual']) ?></td>
                        <td><?= esc($p['estoque_minimo']) ?></td>
                        <td><?= $status ?></td>

                        <td class="text-end">

                            <a href="<?= site_url('pecas/editar/' . $p['id']) ?>"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <a href="<?= site_url('pecas/excluir/' . $p['id']) ?>"
                               onclick="return confirm('Tem certeza que deseja excluir esta peça?')"
                               class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>

                        </td>
                    </tr>

                <?php endforeach ?>

            </tbody>
        </table>

    </div>
</div>

<?= $this->endSection() ?>
