<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * TELA DE REPOSIÇÃO DE ESTOQUE
 * =========================================================
 * Esta tela é acessada quando:
 * - Estoque está crítico
 * - Estoque está zerado (urgente)
 *
 * Ela NÃO movimenta estoque sozinha.
 * Apenas prepara a ação.
 */
?>

<div class="mb-4">
    <h3 class="mb-1">
        <i class="bi bi-box-arrow-in-down"></i>
        Repor Estoque
    </h3>
    <p class="text-muted mb-0">
        Peça: <strong><?= esc($peca['nome']) ?></strong> —
        SKU: <?= esc($peca['sku']) ?>
    </p>
</div>

<?php if (!empty($urgente)): ?>
    <!-- ALERTA DE URGÊNCIA -->
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-octagon"></i>
        <strong>Reposição urgente!</strong><br>
        Esta peça está com estoque zerado e precisa de atenção imediata.
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="post" action="<?= site_url('estoque/processarReposicao') ?>">

            <!-- ID DA PEÇA (SEGURANÇA) -->
            <input type="hidden" name="peca_id" value="<?= $peca['id'] ?>">

            <!-- QUANTIDADE -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Quantidade a adicionar
                </label>
                <input type="number"
                       name="quantidade"
                       class="form-control"
                       min="1"
                       required
                       placeholder="Ex: 10">
            </div>

            <!-- MOTIVO -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Motivo da reposição
                </label>
                <textarea name="motivo"
                          class="form-control"
                          rows="3"
                          required
                          placeholder="Ex: Compra de fornecedor, reposição preventiva, urgência..."></textarea>
            </div>

            <!-- BOTÕES -->
            <div class="d-flex gap-2">
                <button class="btn btn-success">
                    <i class="bi bi-check-circle"></i>
                    Confirmar reposição
                </button>

                <a href="<?= site_url('pecas') ?>" class="btn btn-outline-secondary">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
