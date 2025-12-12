<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<!-- ===========================
     ESTILO TOOLTIP FUTURISTA
=========================== -->
<style>
.tooltip {
    opacity: 0 !important;
    transition: opacity .25s ease, transform .25s ease !important;
    transform: translateY(6px) scale(0.96);
}

.tooltip.show {
    opacity: 1 !important;
    transform: translateY(0) scale(1);
}

.tooltip-inner {
    background: rgba(10, 10, 15, 0.85) !important;
    backdrop-filter: blur(5px);
    color: #e7e7e7 !important;
    max-width: 280px;
    padding: 16px 18px;
    border-radius: 14px;
    font-size: 0.95rem;
    line-height: 1.45;
    text-align: left;
    box-shadow: 0 0 18px rgba(0,255,255,0.25), 
                inset 0 0 10px rgba(0,255,255,0.15);
    border: 1px solid rgba(0,255,255,0.35);
    position: relative;
}

/* Borda neon animada */
.tooltip-inner::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 14px;
    padding: 1px;
    background: linear-gradient(135deg,
        rgba(0,255,255,0.9),
        rgba(0,120,255,0.6),
        rgba(0,255,180,0.9)
    );
  /* Propriedade padrão recomendada pelo VSCode */
mask:
    linear-gradient(#fff 0 0) content-box,
    linear-gradient(#fff 0 0) border-box;

/* Fallback para navegadores WebKit */
-webkit-mask:
    linear-gradient(#fff 0 0) content-box,
    linear-gradient(#fff 0 0) border-box;

-webkit-mask-composite: xor;
mask-composite: exclude;

animation: neonPulse 3s infinite linear;

}

@keyframes neonPulse {
    0% { opacity: 0.55; }
    50% { opacity: 1; }
    100% { opacity: 0.55; }
}

.tooltip .arrow::before {
    border-top-color: rgba(0,255,255,0.4) !important;
}
</style>


<h3 class="mb-4">Dashboard de Estoque</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <h5>Total de Peças</h5>
            <h2 class="text-primary"><?= $total_pecas ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <h5>Estoque Crítico</h5>
            <h2 class="text-danger"><?= $criticos ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm text-center p-3">
            <h5>Próximo do Mínimo</h5>
            <h2 class="text-warning"><?= $atencao ?></h2>
        </div>
    </div>

</div>

<hr class="my-4">

<h4 class="mb-3">Últimas Movimentações</h4>


<!-- seletor de limite -->
<div class="mb-3">
    <form method="get" class="d-flex align-items-center gap-2">

        <label class="fw-bold">Mostrar:</label>

        <select name="limite" class="form-select w-auto"
            onchange="window.location.href='/estoque/dashboard/'+this.value">

            <option value="10"  <?= $limite_atual==10  ? 'selected':'' ?>>10</option>
            <option value="25"  <?= $limite_atual==25  ? 'selected':'' ?>>25</option>
            <option value="50"  <?= $limite_atual==50  ? 'selected':'' ?>>50</option>
            <option value="100" <?= $limite_atual==100 ? 'selected':'' ?>>100</option>

        </select>

    </form>
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
                        /* Badge de status */
                        $statusBadge = $m['estoque_atual'] <= $m['estoque_minimo']
                            ? "<span class='badge bg-danger'>Crítico</span>"
                            : "<span class='badge bg-success'>OK</span>";

                        /* Ícones */
                        $icone = $m['tipo'] === 'entrada'
                            ? "<i class='bi bi-arrow-down-circle text-success fs-4'></i>"
                            : "<i class='bi bi-arrow-up-circle text-danger fs-4'></i>";

                        /* Cor visual da qtd */
                        if ($m['estoque_atual'] <= $m['estoque_minimo']) {
                            $corQtd = "text-danger fw-bold";
                        } elseif ($m['estoque_atual'] <= $m['estoque_minimo'] + 2) {
                            $corQtd = "text-warning fw-bold";
                        } else {
                            $corQtd = "text-success fw-bold";
                        }

                        /* Cálculo do saldo */
                        if ($m['tipo'] === 'entrada') {
                            $saldoAntes  = $m['estoque_atual'] - $m['quantidade'];
                            $saldoDepois = $m['estoque_atual'];
                        } else {
                            $saldoAntes  = $m['estoque_atual'] + $m['quantidade'];
                            $saldoDepois = $m['estoque_atual'];
                        }

                        /* Tooltip Futurista com Ícones */
                        $tooltipHTML = "
                        <div>
                            <div style='margin-bottom:6px;'>
                                <i class=\"bi bi-box-arrow-in-down text-info\"></i>
                                <strong style='color:#0dcaf0;'> Saldo Antes:</strong> {$saldoAntes}
                            </div>

                            <div style='margin-bottom:6px;'>
                                <i class='bi " . ($m['tipo']==='entrada' 
                                    ? "bi-arrow-down-circle text-success" 
                                    : "bi-arrow-up-circle text-danger") . "'></i>
                                <strong style='color:#ffc107;'> Movimentado:</strong>
                                <span style='color:" . ($m['tipo']==='entrada' ? "#0f0" : "#f00") . ";'>
                                    " . ($m['tipo']==='entrada' ? "+" : "-") . "{$m['quantidade']}
                                </span>
                            </div>

                            <div>
                                <i class='bi bi-check2-circle text-primary'></i>
                                <strong style='color:#0d6efd;'> Saldo Depois:</strong> {$saldoDepois}
                            </div>
                        </div>";
                    ?>

                    <tr>

                        <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>

                        <td><?= $icone ?></td>

                        <!-- QTD COM TOOLTIP FUTURISTA -->
                        <td class="<?= $corQtd ?>"
                            data-tooltip='<?= htmlspecialchars($tooltipHTML, ENT_QUOTES) ?>'>
                            <?= $m['quantidade'] ?>
                        </td>

                        <td>
                            <strong><?= $m['nome_peca'] ?></strong><br>
                         
                        </td>

                        <td><?= $statusBadge ?></td>

                        <td><?= $m['motivo'] ?></td>

                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>


<!-- ===========================
     JAVASCRIPT DO TOOLTIP PREMIUM
=========================== -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll("[data-tooltip]").forEach(function(el) {

        new bootstrap.Tooltip(el, {
            html: true,
            title: el.getAttribute("data-tooltip"),
            placement: "top",
            trigger: "hover",
            delay: { show: 120, hide: 80 }
        });

    });

});
</script>

<?= $this->endSection() ?>
