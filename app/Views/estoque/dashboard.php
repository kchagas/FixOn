<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Dashboard de Estoque</h3>

<!-- ===========================
     KPIs OPERACIONAIS
=========================== -->
<div class="row g-4 mb-4">

    <!-- TOTAL DE PEÇAS -->
    <div class="col-md-3">
        <a href="<?= site_url('pecas') ?>" class="kpi-link">
        <div class="kpi-card">
            <span class="kpi-label">Total de Peças</span>
            <div class="kpi-row">
                <h3 class="text-primary"><?= $total_pecas ?></h3>
                <i class="bi bi-box-seam text-primary"></i>
            </div>
        </div>
        </a>
    </div>

    <!-- ESTOQUE CRÍTICO -->
    <div class="col-md-3">
         <a href="<?= site_url('pecas?status=critico') ?>" class="kpi-link">
        <div class="kpi-card">
            <span class="kpi-label">Estoque Crítico</span>
            <div class="kpi-row">
                <h3 class="text-danger"><?= $criticos ?></h3>
                <i class="bi bi-exclamation-triangle text-danger"></i>
            </div>
        </div>
        </a>
    </div>

    <!-- EM ATENÇÃO -->
    <div class="col-md-3">
         <a href="<?= site_url('pecas?status=atencao') ?>" class="kpi-link">
        <div class="kpi-card">
            <span class="kpi-label">Próximo do Mínimo</span>
            <div class="kpi-row">
                <h3 class="text-warning"><?= $atencao ?></h3>
                <i class="bi bi-hourglass-split text-warning"></i>
            </div>
        </div>
        </a>
    </div>

    <!-- ZERADAS -->
    <div class="col-md-3">
         <a href="<?= site_url('pecas?status=zerado') ?>" class="kpi-link">
        <div class="kpi-card">
            <span class="kpi-label">Peças Zeradas</span>
            <div class="kpi-row">
                <h3 class="text-secondary"><?= $pecas_zeradas ?></h3>
                <i class="bi bi-x-octagon text-secondary"></i>
            </div>
        </div>
        </a>
    </div>

</div>

<!-- ===========================
     KPIs FINANCEIROS
=========================== -->
<div class="row g-4 mb-4">

    <!-- VALOR EM ESTOQUE (CUSTO) -->
    <div class="col-md-4">
        <a href="<?= site_url('relatorios/estoque') ?>" class="kpi-link">
        <div class="kpi-card kpi-finance">
            <span class="kpi-label">Valor em Estoque (Custo)</span>
            <div class="kpi-row">
                <h4 class="text-success">
                    R$ <?= number_format($valor_estoque, 2, ',', '.') ?>
                </h4>
                <i class="bi bi-cash-stack text-success"></i>
            </div>
            <small class="kpi-label">Dinheiro imobilizado</small>
        </div>
        </a>
    </div>

    <!-- VALOR POTENCIAL DE VENDA -->
    <div class="col-md-4">
        <div class="kpi-card kpi-finance">
            <span class="kpi-label">Valor Potencial de Venda</span>
            <div class="kpi-row">
                <h4 class="text-info">
                    R$ <?= number_format($valor_potencial_venda, 2, ',', '.') ?>
                </h4>
                <i class="bi bi-graph-up-arrow text-info"></i>
            </div>
            <small class="kpi-label">Receita estimada</small>
        </div>
    </div>

</div>

<hr class="my-4">

<!-- ===========================
     ÚLTIMAS MOVIMENTAÇÕES
=========================== -->
<h4 class="mb-3">Últimas Movimentações</h4>

<div class="mb-3">
    <label class="fw-bold me-2">Mostrar:</label>
    <select class="form-select d-inline w-auto"
        onchange="window.location.href='/estoque/dashboard/'+this.value">
        <option value="10"  <?= $limite_atual==10?'selected':'' ?>>10</option>
        <option value="25"  <?= $limite_atual==25?'selected':'' ?>>25</option>
        <option value="50"  <?= $limite_atual==50?'selected':'' ?>>50</option>
        <option value="100" <?= $limite_atual==100?'selected':'' ?>>100</option>
    </select>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-dark table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Mov.</th>
                    <th>Qtd</th>
                    <th>Peça</th>
                    <th>Status</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($ultimas_mov as $m): ?>
                    <?php
                        $status = ($m['estoque_atual'] <= $m['estoque_minimo'])
                            ? "<span class='badge bg-danger'>Crítico</span>"
                            : "<span class='badge bg-success'>OK</span>";

                        $icone = ($m['tipo'] === 'entrada')
                            ? "<i class='bi bi-arrow-down-circle text-success fs-4'></i>"
                            : "<i class='bi bi-arrow-up-circle text-danger fs-4'></i>";
                    ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                        <td><?= $icone ?></td>
                        <td class="fw-bold"><?= $m['quantidade'] ?></td>
                        <td>
                            <strong><?= esc($m['nome_peca']) ?></strong><br>
                            <small class="text-muted">SKU: <?= esc($m['sku']) ?></small>
                        </td>
                        <td><?= $status ?></td>
                        <td><?= esc($m['motivo']) ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
