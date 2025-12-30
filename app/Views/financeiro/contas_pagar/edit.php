<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * CONTAS A PAGAR — EDITAR / PAGAMENTO
 * =========================================================
 */
?>

<h3 class="mb-3">Editar Despesa</h3>

<form action="<?= site_url('financeiro/contas-pagar/update/'.$conta['id']) ?>" method="post">

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label fw-semibold">Descrição</label>
                <input type="text" name="descricao"
                       class="form-control"
                       value="<?= esc($conta['descricao']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Valor</label>
                <input type="number" step="0.01" name="valor"
                       class="form-control"
                       value="<?= $conta['valor'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Data de Vencimento</label>
                <input type="date" name="data_vencimento"
                       class="form-control"
                       value="<?= $conta['data_vencimento'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Data de Pagamento</label>
                <input type="date" name="data_pagamento"
                       class="form-control"
                       value="<?= $conta['data_pagamento'] ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Observações</label>
                <textarea name="observacoes"
                          class="form-control"
                          rows="3"><?= esc($conta['observacoes']) ?></textarea>
            </div>

        </div>

        <div class="card-footer text-end">
            <a href="<?= site_url('financeiro/contas-pagar') ?>" class="btn btn-secondary">
                Voltar
            </a>

            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Atualizar
            </button>
        </div>
    </div>

</form>

<?= $this->endSection() ?>
