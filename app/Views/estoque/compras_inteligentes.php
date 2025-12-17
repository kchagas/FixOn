<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * COMPRAS INTELIGENTES (PASSO 5.1)
 * ---------------------------------------------------------
 * ✔ Apenas itens com compra sugerida
 * ✔ Consumo/dia em inteiro
 * ✔ Previsão humana
 * ✔ Foco em decisão (não matemática)
 * =========================================================
 */
?>

<!-- =======================
     CABEÇALHO
======================= -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Compras Inteligentes</h3>
        <small class="text-muted">
            Cobertura sugerida: <?= (int)$config['cobertura_reposicao_dias'] ?> dias ·
            Consumo calculado por <?= (int)$config['janela_consumo_dias'] ?> dias
        </small>
    </div>

    <a href="<?= site_url('estoque/alertas') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar aos alertas
    </a>
</div>

<!-- =======================
     TABELA DE COMPRAS
======================= -->
<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-dark table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nível</th>
                    <th>Peça</th>
                    <th>Estoque</th>
                    <th>Consumo/dia</th>
                    <th>Previsão</th>
                    <th>Qtd sugerida</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($compras)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted p-4">
                        Nada para comprar agora ✅
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($compras as $c): ?>

                    <?php
                        $badge = match ($c['nivel']) {
                            'urgente' => "<span class='badge bg-danger'>Urgente</span>",
                            'critico' => "<span class='badge bg-warning text-dark'>Crítico</span>",
                            'atencao' => "<span class='badge bg-info text-dark'>Atenção</span>",
                            default   => "<span class='badge bg-success'>OK</span>",
                        };

                        if ($c['dias_para_zerar'] === null || $c['dias_para_zerar'] <= 0) {
                            $previsao = "<span class='fw-bold text-danger'>Hoje</span>";
                        } else {
                            $d = (int) ceil($c['dias_para_zerar']);
                            $previsao = match (true) {
                                $d === 1 => 'Amanhã',
                                $d <= 7  => "Em {$d} dias",
                                $d <= 30 => "Em cerca de {$d} dias",
                                default  => 'Mais de 30 dias',
                            };
                        }
                    ?>

                    <tr>
                        <td><?= $badge ?></td>

                        <td>
                            <strong><?= esc($c['nome']) ?></strong><br>
                            <small class="text-secondary fw-medium">
                                SKU: <?= esc($c['sku']) ?>
                            </small>
                        </td>

                        <td>
                            <span class="fw-bold"><?= (int)$c['estoque_atual'] ?></span>
                            <small class="text-secondary fw-semibold">
                                / mín: <?= (int)$c['estoque_minimo'] ?>
                            </small>
                        </td>

                        <!-- CONSUMO/DIA (INTEIRO) -->
                        <td>
                            <?= (int) ceil($c['consumo_medio_dia']) ?>
                            <small class="text-secondary">/dia</small>
                        </td>

                        <td><?= $previsao ?></td>

                        <td>
                            <span class="badge bg-success">
                                +<?= (int)$c['qtd_sugerida'] ?>
                            </span>
                        </td>
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>

        </table>

    </div>
</div>

<?= $this->endSection() ?>
