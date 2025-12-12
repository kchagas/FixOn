<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('erro_exclusao')): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalHtml = `
    <div class="modal fade" id="erroModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" 
                style="border-radius: 18px; background:#fdfdfd;">

                <div class="modal-body text-center p-5">

                    <div class="mb-3">
                        <i class="bi bi-exclamation-circle" 
                           style="font-size:4rem; color:#333;"></i>
                    </div>

                    <h4 class="fw-semibold mb-3" style="color:#222;">
                        Operação não permitida
                    </h4>

                    <p class="text-muted" style="font-size:1.15rem; line-height:1.6;">
                        Esta peça não pode ser excluída porque já possui
                        <strong>movimentações registradas no estoque</strong>.
                        <br><br>
                        Para garantir a integridade do histórico e dos relatórios,
                        itens que já foram movimentados permanecem protegidos
                        contra exclusão.
                    </p>

                </div>

                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button class="btn btn-dark px-4 fw-bold" data-bs-dismiss="modal" 
                        style="border-radius: 10px;">
                        Entendi
                    </button>
                </div>
            </div>
        </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', modalHtml);
    new bootstrap.Modal(document.getElementById('erroModal')).show();
});
</script>
<?php endif; ?>


<h3 class="mb-1">Peças</h3>
<h5 class="text-muted mb-4">Peças Cadastradas</h5>

<a href="<?= site_url('pecas/cadastrar') ?>" class="btn btn-success mb-3">
    + Nova Peça
</a>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <table id="tabelaPecas" class="table table-striped table-dark table-hover" style="width:100%">
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
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Deseja realmente excluir esta peça?')">
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
