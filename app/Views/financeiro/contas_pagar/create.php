<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * CONTAS A PAGAR — NOVA DESPESA
 * =========================================================
 */
?>

<h3 class="mb-3">Nova Despesa</h3>

<form action="<?= site_url('financeiro/contas-pagar/store') ?>" method="post">

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="mb-3">
                <label class="form-label fw-semibold">Descrição</label>
                <input type="text" name="descricao" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Valor</label>
                <input type="number" step="0.01" name="valor" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Data de Vencimento</label>
                <input type="date" name="data_vencimento" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"></textarea>
            </div>

        </div>

        <div class="card-footer text-end">
            <a href="<?= site_url('financeiro/contas-pagar') ?>" class="btn btn-secondary">
                Cancelar
            </a>

            <button class="btn btn-success">
                <i class="bi bi-save"></i> Salvar
            </button>
        </div>
    </div>

</form>

<?= $this->endSection() ?>
