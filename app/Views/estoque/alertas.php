<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * =========================================================
 * CENTRAL DE ALERTAS (PASSO 5.1)
 * ---------------------------------------------------------
 * ✔ Linguagem humana (sem dias quebrados)
 * ✔ Consumo/dia em número inteiro
 * ✔ Previsão amigável (Hoje / Amanhã / Em X dias)
 * ✔ Mensagens orientadas à decisão
 * ✔ Alerta com auto-dismiss + botão fechar
 * =========================================================
 */
?>

<!-- =======================
     CABEÇALHO
======================= -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Central de Alertas</h3>
        <small class="text-secondary fw-semibold">
            Janela de consumo: <?= (int)$config['janela_consumo_dias'] ?> dias ·
            Cobertura sugerida: <?= (int)$config['cobertura_reposicao_dias'] ?> dias
        </small>
    </div>

    <div class="d-flex gap-2">
        <a href="<?= site_url('estoque/gerar-alertas') ?>" class="btn btn-outline-info">
            <i class="bi bi-arrow-repeat"></i> Atualizar alertas
        </a>

        <a href="<?= site_url('estoque/compras') ?>" class="btn btn-success">
            <i class="bi bi-cart-check"></i> Compras Inteligentes
        </a>
    </div>
</div>

<!-- =======================
     ALERTA DE SUCESSO
======================= -->
<?php if (session()->getFlashdata('success')): ?>
<div id="alert-success"
     class="alert alert-success alert-dismissible fade show d-flex align-items-center"
     role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>

    <div class="flex-grow-1">
        <?= session()->getFlashdata('success') ?>
    </div>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- =======================
     TABELA DE ALERTAS
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
                    <th>Sugestão</th>
                    <th>Mensagem</th>
                </tr>
            </thead>

            <tbody>
            <?php if (empty($alertas)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted p-4">
                        Nenhum alerta no momento ✅
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($alertas as $a): ?>

                    <?php
                        /**
                         * ------------------------------------------
                         * BADGE DE NÍVEL
                         * ------------------------------------------
                         */
                        $badge = match ($a['nivel']) {
                            'urgente' => "<span class='badge bg-danger'>Urgente</span>",
                            'critico' => "<span class='badge bg-warning text-dark'>Crítico</span>",
                            'atencao' => "<span class='badge bg-info text-dark'>Atenção</span>",
                            default   => "<span class='badge bg-success'>OK</span>",
                        };

                        /**
                         * ------------------------------------------
                         * PREVISÃO EM LINGUAGEM HUMANA
                         * ------------------------------------------
                         */
                        if ($a['dias_para_zerar'] === null || $a['dias_para_zerar'] <= 0) {
                            $previsao = "<span class='fw-bold text-danger'>Hoje</span>";
                        } else {
                            $d = (int) ceil($a['dias_para_zerar']);
                            $previsao = match (true) {
                                $d === 1 => 'Amanhã',
                                $d <= 7  => "Em {$d} dias",
                                $d <= 30 => "Em cerca de {$d} dias",
                                default  => 'Mais de 30 dias',
                            };
                        }

                        /**
                         * ------------------------------------------
                         * MENSAGEM ORIENTADA À DECISÃO
                         * ------------------------------------------
                         */
                        $mensagem = match ($a['nivel']) {
                            'urgente' => 'Urgente: risco imediato de falta.',
                            'critico' => "Crítico: previsão de falta em cerca de {$d} dias.",
                            'atencao' => "Atenção: tendência de queda em cerca de {$d} dias.",
                            default   => $a['mensagem'],
                        };
                    ?>

                    <tr>
                        <td><?= $badge ?></td>

                        <td>
                            <strong><?= esc($a['nome']) ?></strong><br>
                            <small class="text-secondary fw-medium">
                                SKU: <?= esc($a['sku']) ?>
                            </small>
                        </td>

                        <td>
                            <span class="fw-bold"><?= (int)$a['estoque_atual'] ?></span>
                            <small class="text-secondary fw-semibold ms-1">
                                mín: <?= (int)$a['estoque_minimo'] ?>
                            </small>
                        </td>

                        <!-- CONSUMO/DIA (INTEIRO) -->
                        <td>
                            <?= (int) ceil($a['consumo_medio_dia']) ?>
                            <small class="text-secondary">/dia</small>
                        </td>

                        <td><?= $previsao ?></td>

                        <td>
                            <?= (int)$a['qtd_sugerida'] > 0
                                ? "<span class='badge bg-success'>+{$a['qtd_sugerida']}</span>"
                                : "<span class='text-muted fst-italic'>—</span>" ?>
                        </td>

                        <td><?= esc($mensagem) ?></td>
                    </tr>

                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>

        </table>

    </div>
</div>

<!-- =======================
     AUTO-DISMISS ALERT
======================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('alert-success');
    if (alert) {
        setTimeout(() => {
            bootstrap.Alert.getOrCreateInstance(alert).close();
        }, 5000);
    }
});
</script>

<?= $this->endSection() ?>
