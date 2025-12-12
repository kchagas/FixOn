<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Peças Cadastradas</h3>

<a href="<?= site_url('pecas/cadastrar') ?>" class="btn btn-success mb-3">
    + Nova Peça
</a>

<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Nome</th>
            <th>Estoque</th>
            <th>Mínimo</th>
            <th>Status</th>
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
                <td><?= $p['sku'] ?></td>
                <td><?= $p['nome'] ?></td>
                <td><?= $p['estoque_atual'] ?></td>
                <td><?= $p['estoque_minimo'] ?></td>
                <td><?= $status ?></td>
            </tr>

        <?php endforeach ?>

    </tbody>
</table>

<?= $this->endSection() ?>
