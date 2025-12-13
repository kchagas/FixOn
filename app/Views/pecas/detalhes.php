<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * ==========================================================
 * TELA DE DETALHES DA PEÇA
 * ----------------------------------------------------------
 * - Exibe informações gerais da peça
 * - Cards de estoque
 * - Histórico de movimentações
 * - Modais para Entrada, Saída e Ajuste de estoque
 * ==========================================================
 */
?>

<!-- =========================
     CABEÇALHO DA PÁGINA
========================= -->
<h3 class="mb-3 d-flex justify-content-between align-items-center">

    <!-- Nome da peça + SKU -->
    <div>
        <?= esc($peca['nome']) ?>
        <small class="text-muted">SKU: <?= esc($peca['sku']) ?></small>
    </div>

    <!-- AÇÕES DE ESTOQUE -->
    <div class="d-flex gap-2">

        <!-- BOTÃO ENTRADA -->
        <button class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modalEntradaEstoque">
            <i class="bi bi-plus-circle"></i> Entrada
        </button>

        <!-- BOTÃO SAÍDA -->
        <button class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#modalSaidaEstoque">
            <i class="bi bi-dash-circle"></i> Saída
        </button>

        <!-- BOTÃO AJUSTE -->
        <button class="btn btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#modalAjusteEstoque">
            <i class="bi bi-sliders"></i> Ajuste
        </button>

    </div>
</h3>

<!-- =========================
     STATUS DA PEÇA
========================= -->
<span class="badge bg-<?= $status['class'] ?> mb-4">
    Status: <?= $status['label'] ?>
</span>

<!-- =========================
     CARDS DE ESTOQUE
========================= -->
<div class="row mb-4">

    <!-- Estoque Atual -->
    <div class="col-md-3">
        <div class="card text-center shadow-sm p-3">
            <h6>Estoque Atual</h6>
            <h2 class="text-primary"><?= $peca['estoque_atual'] ?></h2>
        </div>
    </div>

    <!-- Estoque Mínimo -->
    <div class="col-md-3">
        <div class="card text-center shadow-sm p-3">
            <h6>Estoque Mínimo</h6>
            <h2 class="text-secondary"><?= $peca['estoque_minimo'] ?></h2>
        </div>
    </div>

    <!-- Total de Entradas -->
    <div class="col-md-3">
        <div class="card text-center shadow-sm p-3">
            <h6>Total Entradas</h6>
            <h2 class="text-success"><?= $totalEntradas ?></h2>
        </div>
    </div>

    <!-- Total de Saídas -->
    <div class="col-md-3">
        <div class="card text-center shadow-sm p-3">
            <h6>Total Saídas</h6>
            <h2 class="text-danger"><?= $totalSaidas ?></h2>
        </div>
    </div>

</div>

<hr>

<!-- =========================
     HISTÓRICO DA PEÇA
========================= -->
<h5 class="mb-3">Histórico da Peça</h5>

<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-dark table-striped mb-0 align-middle" id="tabelaDetalhes">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Qtd</th>
                    <th>Usuário</th>
                    <th>Motivo</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($movimentacoes as $m): ?>
                    <tr>

                        <!-- Data -->
                        <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>

                        <!-- Tipo -->
                        <td>
                            <?php if ($m['tipo'] === 'entrada'): ?>
                                <span class="text-success">
                                    <i class="bi bi-arrow-down-circle"></i> Entrada
                                </span>
                            <?php elseif ($m['tipo'] === 'saida'): ?>
                                <span class="text-danger">
                                    <i class="bi bi-arrow-up-circle"></i> Saída
                                </span>
                            <?php else: ?>
                                <span class="text-warning">
                                    <i class="bi bi-sliders"></i> Ajuste
                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Quantidade -->
                        <td><?= $m['quantidade'] ?></td>

                        <!-- Usuário -->
                        <td><?= esc($m['nome_usuario'] ?? 'Sistema') ?></td>

                        <!-- Motivo -->
                        <td><?= esc($m['motivo']) ?></td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>

<!-- =========================
     MODAL ENTRADA
========================= -->
<div class="modal fade" id="modalEntradaEstoque" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post"
          action="<?= site_url('movimentacoes/salvarEntrada') ?>"
          class="modal-content bg-dark text-light">

        <div class="modal-header">
            <h5 class="modal-title text-success">
                <i class="bi bi-plus-circle"></i> Entrada de Estoque
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <!-- ID da peça -->
            <input type="hidden" name="peca_id" value="<?= $peca['id'] ?>">

            <!-- Quantidade -->
            <div class="mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" name="quantidade" class="form-control" min="1" required>
            </div>

            <!-- Motivo -->
            <div class="mb-3">
                <label class="form-label">Motivo</label>
                <textarea name="motivo" class="form-control" required></textarea>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-success">Confirmar Entrada</button>
        </div>

    </form>
  </div>
</div>

<!-- =========================
     MODAL SAÍDA
========================= -->
<div class="modal fade" id="modalSaidaEstoque" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post"
          action="<?= site_url('movimentacoes/salvarSaida') ?>"
          class="modal-content bg-dark text-light">

        <div class="modal-header">
            <h5 class="modal-title text-danger">
                <i class="bi bi-dash-circle"></i> Saída de Estoque
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <input type="hidden" name="peca_id" value="<?= $peca['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" name="quantidade" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Motivo</label>
                <textarea name="motivo" class="form-control" required></textarea>
            </div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-danger">Confirmar Saída</button>
        </div>

    </form>
  </div>
</div>

                                <!-- =========================
     MODAL AJUSTE DE ESTOQUE
========================= -->
<div class="modal fade" id="modalAjusteEstoque" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">

    <!-- 
        IMPORTANTE:
        - Ajuste SEMPRE é POST
        - Vai para controller Estoque::ajustar()
    -->
    <form method="post"
          action="<?= site_url('estoque/ajustar') ?>"
          class="modal-content bg-dark text-light shadow-lg">

      <!-- CABEÇALHO -->
      <div class="modal-header border-secondary">
        <h5 class="modal-title text-warning">
            <i class="bi bi-sliders"></i> Ajuste de Estoque
        </h5>

        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <!-- CORPO -->
      <div class="modal-body">

        <!-- ID DA PEÇA (OBRIGATÓRIO) -->
        <input type="hidden" name="peca_id" value="<?= $peca['id'] ?>">

        <!-- ESTOQUE ATUAL (SOMENTE LEITURA) -->
        <div class="mb-3">
            <label class="form-label text-muted fw-bold">Estoque Atual</label>
            <input type="number"
                   class="form-control bg-secondary text-light border-0"
                   value="<?= $peca['estoque_atual'] ?>"
                   readonly>
        </div>

        <!-- NOVO ESTOQUE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Novo Estoque</label>
            <input type="number"
                   name="novo_estoque"
                   class="form-control bg-dark text-light border border-warning"
                   min="0"
                   placeholder="Informe o novo saldo"
                   required>
        </div>

        <!-- MOTIVO -->
        <div class="mb-3">
            <label class="form-label fw-bold">Motivo do Ajuste</label>
            <textarea name="motivo"
                      class="form-control bg-dark text-light border border-secondary"
                      rows="3"
                      placeholder="Ex: inventário físico, avaria, correção manual"
                      required></textarea>
        </div>

        <!-- ALERTA -->
        <div class="alert alert-warning d-flex align-items-center gap-2 small">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Esta ação será registrada no histórico e não poderá ser apagada.
        </div>

      </div>

      <!-- RODAPÉ -->
      <div class="modal-footer border-secondary">
        <button type="button"
                class="btn btn-outline-light"
                data-bs-dismiss="modal">
            Cancelar
        </button>

        <button type="submit" class="btn btn-warning fw-bold">
            <i class="bi bi-check-circle"></i> Confirmar Ajuste
        </button>
      </div>

    </form>
  </div>
</div>


<!-- =========================
     DATATABLE
========================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof $ !== 'undefined') {
        $('#tabelaDetalhes').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
            },
            order: [[0, 'desc']]
        });
    }
});
</script>

<?= $this->endSection() ?>
