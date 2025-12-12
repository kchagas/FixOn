<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Relatório de Movimentações de Estoque</h3>

<!-- FILTROS -->
<div class="card mb-3 shadow-sm">
    <div class="card-body">

        <form method="get" class="row g-3 align-items-end">

            <!-- Peça -->
            <div class="col-md-4">
                <label class="form-label">Peça</label>
                <select name="peca_id" class="form-select">
                    <option value="">Todas</option>
                    <?php foreach ($pecas as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= ($filtro_peca == $p['id']) ? 'selected' : '' ?>>
                            <?= esc($p['sku']) ?> - <?= esc($p['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tipo -->
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <option value="entrada" <?= $filtro_tipo === 'entrada' ? 'selected' : '' ?>>Entradas</option>
                    <option value="saida"   <?= $filtro_tipo === 'saida'   ? 'selected' : '' ?>>Saídas</option>
                </select>
            </div>

            <!-- Usuário -->
            <div class="col-md-3">
                <label class="form-label">Usuário</label>
                <select name="usuario_id" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($usuarios as $u): ?>
                        <option value="<?= $u['id'] ?>"
                            <?= ($filtro_usuario == $u['id']) ? 'selected' : '' ?>>
                            <?= esc($u['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Data início -->
            <div class="col-md-1">
                <label class="form-label">Início</label>
                <input type="date" name="data_inicio" value="<?= esc($filtro_ini) ?>" class="form-control">
            </div>

            <!-- Data fim -->
            <div class="col-md-1">
                <label class="form-label">Fim</label>
                <input type="date" name="data_fim" value="<?= esc($filtro_fim) ?>" class="form-control">
            </div>

            <!-- Botões -->
            <div class="col-md-1 d-grid">
                <button class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>

            <div class="col-md-12 text-end">
                <a href="<?= site_url('estoque/relatorio') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Limpar filtros
                </a>
            </div>

        </form>

    </div>
</div>

<!-- TABELA -->
<div class="card shadow-sm">
    <div class="card-body p-0">

        <table id="tabelaMov" class="table table-dark table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Qtd</th>
                    <th>Peça</th>
                    <th>Usuário</th>
                    <th>Motivo</th>
                    <th>Estoque Após</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($movimentacoes as $m): ?>

                    <?php
                        // STATUS geral da peça
                        $statusBadge = $m['estoque_atual'] <= $m['estoque_minimo']
                            ? "<span class='badge bg-danger'>Crítico</span>"
                            : "<span class='badge bg-success'>OK</span>";

                        // ícone tipo
                        $tipoLabel = $m['tipo'] === 'entrada'
                            ? "<span class='text-success'><i class='bi bi-arrow-down-circle'></i> Entrada</span>"
                            : "<span class='text-danger'><i class='bi bi-arrow-up-circle'></i> Saída</span>";

                        // cor Qtd
                        if ($m['estoque_atual'] <= $m['estoque_minimo']) {
                            $corQtd = "text-danger fw-bold";
                        } elseif ($m['estoque_atual'] <= $m['estoque_minimo'] + 2) {
                            $corQtd = "text-warning fw-bold";
                        } else {
                            $corQtd = "text-success fw-bold";
                        }
                    ?>

                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>

                        <td><?= $tipoLabel ?></td>

                        <td class="<?= $corQtd ?>">
                            <?= $m['quantidade'] ?>
                        </td>

                        <td>
                            <strong><?= esc($m['nome_peca']) ?></strong><br>
                            <small class="text-muted">SKU: <?= esc($m['sku']) ?></small>
                        </td>

                        <td><?= esc($m['nome_usuario'] ?? '—') ?></td>

                        <td><?= esc($m['motivo']) ?></td>

                        <td><?= (int)$m['estoque_atual'] ?></td>

                        <td><?= $statusBadge ?></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>

<!-- DataTables desta página -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof $ !== 'undefined' && $('#tabelaMov').length) {
        $('#tabelaMov').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
            },
            pageLength: 25,
            order: [[0, 'desc']]
        });
    }
});
</script>

<?= $this->endSection() ?>
