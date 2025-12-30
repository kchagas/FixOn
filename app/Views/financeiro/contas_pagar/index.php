<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * CONTAS A PAGAR — LISTAGEM AVANÇADA
 * ---------------------------------------------------------
 * Recursos:
 * - Filtro por status
 * - Filtro por descrição
 * - Filtro por data de vencimento
 * - Ordenação por data e valor
 * =========================================================
 */
?>

<!-- ===========================
     CABEÇALHO
=========================== -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Contas a Pagar</h3>
        <small class="text-muted">Controle financeiro de despesas</small>
    </div>

    <a href="<?= site_url('financeiro/contas-pagar/create') ?>" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nova Despesa
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div id="alert-success"
     class="alert alert-success alert-dismissible fade show d-flex align-items-center"
     role="alert">

    <i class="bi bi-check-circle-fill me-2"></i>

    <div class="flex-grow-1">
        <?= session()->getFlashdata('success') ?>
    </div>

    <!-- Botão fechar -->
    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"></button>
</div>
<?php endif; ?>


<!-- =========================================
     FILTROS — PADRÃO SAAS PROFISSIONAL
========================================= -->
<form method="get" class="card shadow-sm mb-4 border-0">

    <div class="card-body py-3">

        <div class="d-flex flex-wrap align-items-end gap-3">

            <!-- STATUS -->
            <div style="min-width:140px">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="aberto"   <?= request()->getGet('status')=='aberto' ? 'selected' : '' ?>>Pendente</option>
                    <option value="pago"     <?= request()->getGet('status')=='pago' ? 'selected' : '' ?>>Paga</option>
                    <option value="atrasado" <?= request()->getGet('status')=='atrasado' ? 'selected' : '' ?>>Vencida</option>
                </select>
            </div>

            <!-- DESCRIÇÃO -->
            <div style="min-width:260px">
                <label class="form-label small mb-1">Descrição</label>
                <input
                    type="text"
                    name="descricao"
                    class="form-control form-control-sm"
                    placeholder="Buscar por descrição"
                    value="<?= esc(request()->getGet('descricao')) ?>">
            </div>

            <!-- DATA INÍCIO -->
            <div style="min-width:150px">
                <label class="form-label small mb-1">Venc. de</label>
                <input
                    type="date"
                    name="data_inicio"
                    class="form-control form-control-sm"
                    value="<?= esc(request()->getGet('data_inicio')) ?>">
            </div>

            <!-- DATA FIM -->
            <div style="min-width:150px">
                <label class="form-label small mb-1">Venc. até</label>
                <input
                    type="date"
                    name="data_fim"
                    class="form-control form-control-sm"
                    value="<?= esc(request()->getGet('data_fim')) ?>">
            </div>

            <!-- ORDENAR POR -->
            <div style="min-width:160px">
                <label class="form-label small mb-1">Ordenar por</label>
                <select name="order" class="form-select form-select-sm">
                    <option value="data_vencimento">Data</option>
                    <option value="valor">Valor</option>
                </select>
            </div>

            <!-- ORDEM -->
            <div style="min-width:90px">
                <label class="form-label small mb-1">Ordem</label>
                <select name="dir" class="form-select form-select-sm">
                    <option value="asc">↑ Crescente</option>
                    <option value="desc">↓ Decrescente</option>
                </select>
            </div>

            <!-- AÇÕES -->
            <div class="ms-auto d-flex gap-2">
                <a href="<?= site_url('financeiro/contas-pagar') ?>"
                   class="btn btn-outline-secondary btn-sm">
                    Limpar
                </a>

                <button class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>

        </div>

    </div>
</form>


<!-- ===========================
     TABELA
=========================== -->
<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-dark table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($contas)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted p-4">
                        Nenhuma conta encontrada
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($contas as $c): ?>

                    <?php
                        // Badge de status
                        if ($c['status'] === 'pago') {
                            $badge = "<span class='badge bg-success'>Paga</span>";
                        } elseif ($c['status'] === 'atrasado') {
                            $badge = "<span class='badge bg-danger'>Vencida</span>";
                        } else {
                            $badge = "<span class='badge bg-warning text-dark'>Pendente</span>";
                        }
                    ?>

                    <tr>
                        <td><?= esc($c['descricao']) ?></td>
                        <td><?= date('d/m/Y', strtotime($c['data_vencimento'])) ?></td>
                        <td>R$ <?= number_format($c['valor'], 2, ',', '.') ?></td>
                        <td><?= $badge ?></td>

                        <td class="text-end">
                            <a href="<?= site_url('financeiro/contas-pagar/edit/'.$c['id']) ?>"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="<?= site_url('financeiro/contas-pagar/delete/'.$c['id']) ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Deseja excluir esta despesa?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>

        </table>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('alert-success');

    if (alert) {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000); // 5 segundos
    }
});
</script>


<?= $this->endSection() ?>
