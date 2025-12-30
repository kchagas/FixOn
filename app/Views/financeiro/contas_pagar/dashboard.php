<?= $this->extend('templates/dashboard') ?> 
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * DASHBOARD FINANCEIRO — CONTAS A PAGAR
 * ---------------------------------------------------------
 * Exibe:
 * - KPIs financeiros
 * - Contas vencendo em 7 dias
 * - Contas vencidas (alertas)
 * =========================================================
 */
?>

<!-- =======================
     CABEÇALHO
======================= -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Dashboard Financeiro</h3>
        <small>Visão geral das contas a pagar</small>
    </div>

    <div class="d-flex gap-2">
        <!-- Lista completa -->
        <a href="<?= site_url('financeiro/contas-pagar') ?>" class="btn btn-outline-light">
            <i class="bi bi-list-check"></i> Ver Contas a Pagar
        </a>

        <!-- Nova despesa -->
        <a href="<?= site_url('financeiro/contas-pagar/create') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nova Despesa
        </a>
    </div>
</div>

<!-- =======================
     CARDS (KPIs)
======================= -->
<div class="row g-3 mb-4">

    <!-- Total em aberto -->
    <div class="col-md-3">
        <div class="card shadow-sm bg-dark text-white">
            <div class="card-body">
                <small>Total em Aberto</small>
                <h4 class="mb-0">
                    R$ <?= number_format($resumo['total_aberto'], 2, ',', '.') ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- Total vencido -->
    <div class="col-md-3">
        <div class="card shadow-sm bg-danger text-white">
            <div class="card-body">
                <small>Total Vencido</small>
                <h4 class="mb-0">
                    R$ <?= number_format($resumo['total_vencido'], 2, ',', '.') ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- Total pago -->
    <div class="col-md-3">
        <div class="card shadow-sm bg-success text-white">
            <div class="card-body">
                <small>Total Pago</small>
                <h4 class="mb-0">
                    R$ <?= number_format($resumo['total_pago'], 2, ',', '.') ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- Quantidade vencendo -->
    <div class="col-md-3">
        <div class="card shadow-sm bg-warning text-dark">
            <div class="card-body">
                <small>Vence em 7 dias</small>
                <h4 class="mb-0">
                    <?= (int)$resumo['qtd_vencendo_7d'] ?> conta(s)
                </h4>
            </div>
        </div>
    </div>

</div>

<!-- =======================
     LISTAGENS
======================= -->
<div class="row g-3">

    <!-- CONTAS VENCENDO -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-calendar-event"></i> Vencendo em 7 dias
            </div>

            <div class="card-body p-0">
                <table class="table table-dark table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Venc.</th>
                            <th class="text-end">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($vencendo)): ?>
                        <tr>
                            <td colspan="3" class="text-center p-4">
                                Nenhuma conta vencendo nos próximos 7 dias ✅
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vencendo as $c): ?>
                            <tr>
                                <td><?= esc($c['descricao']) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['data_vencimento'])) ?></td>
                                <td class="text-end">
                                    R$ <?= number_format($c['valor'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- BOTÃO FILTRADO -->
            <div class="card-footer text-end">
                <a href="<?= site_url('financeiro/contas-pagar?filtro=vencendo') ?>"
                   class="btn btn-sm btn-outline-secondary">
                    Ver contas vencendo
                </a>
            </div>
        </div>
    </div>

    <!-- CONTAS VENCIDAS -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle"></i> Contas Vencidas
            </div>

            <div class="card-body p-0">
                <table class="table table-dark table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Venc.</th>
                            <th class="text-end">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($vencidas)): ?>
                        <tr>
                            <td colspan="3" class="text-center p-4">
                                Nenhuma conta vencida ✅
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vencidas as $c): ?>
                            <tr>
                                <td><?= esc($c['descricao']) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['data_vencimento'])) ?></td>
                                <td class="text-end">
                                    R$ <?= number_format($c['valor'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- BOTÃO FILTRADO -->
            <div class="card-footer text-end">
                <a href="<?= site_url('financeiro/contas-pagar?filtro=vencidas') ?>"
                   class="btn btn-sm btn-light">
                    Ver contas vencidas
                </a>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
