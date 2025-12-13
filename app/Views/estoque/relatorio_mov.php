<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<!-- =========================================================
     RELATÓRIO DE MOVIMENTAÇÕES DE ESTOQUE
     - Filtros por peça, tipo, usuário e período
     - Tabela com DataTables (paginação, ordenação, busca)
========================================================= -->

<h3 class="mb-4">Relatório de Movimentações de Estoque</h3>

<a href="<?= site_url('estoque/relatorio/pdf?' . http_build_query($_GET)) ?>"
   target="_blank"
   class="btn btn-danger mb-3">
    <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
</a>


<!-- =========================================================
     BLOCO DE FILTROS
     - Envia via GET para manter filtros na URL
     - Permite filtrar por peça, tipo, usuário e datas
========================================================= -->
<div class="card mb-3 shadow-sm">
    <div class="card-body">

        <form method="get" class="row g-3 align-items-end">

            <!-- =========================
                 FILTRO: PEÇA
                 - Lista todas as peças disponíveis
                 - Mantém selecionado com $filtro_peca
            ========================== -->
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

            <!-- =========================
                 FILTRO: TIPO
                 - Agora inclui "ajuste"
                 - Mantém selecionado com $filtro_tipo
            ========================== -->
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>

                    <!-- Entrada -->
                    <option value="entrada" <?= $filtro_tipo === 'entrada' ? 'selected' : '' ?>>
                        Entradas
                    </option>

                    <!-- Saída -->
                    <option value="saida" <?= $filtro_tipo === 'saida' ? 'selected' : '' ?>>
                        Saídas
                    </option>

                    <!-- Ajuste (NOVO) -->
                    <option value="ajuste" <?= $filtro_tipo === 'ajuste' ? 'selected' : '' ?>>
                        Ajustes
                    </option>
                </select>
            </div>

            <!-- =========================
                 FILTRO: USUÁRIO
                 - Lista usuários cadastrados
                 - Mantém selecionado com $filtro_usuario
            ========================== -->
            <div class="col-md-2">
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

            <!-- =========================
                 FILTRO: DATA INÍCIO
                 - Filtra created_at >= data_inicio
            ========================== -->
           <!-- DATA INÍCIO -->
<div class="col-md-2">
    <label class="form-label fw-semibold">Início</label>
    <input 
        type="date"
        name="data_inicio"
        value="<?= esc($filtro_ini) ?>"
        class="form-control"
    >
</div>


            <!-- =========================
                 FILTRO: DATA FIM
                 - Filtra created_at <= data_fim
            ========================== -->
            <div class="col-md-2">
                <label class="form-label">Fim</label>
                <input type="date" name="data_fim"
                       value="<?= esc($filtro_fim) ?>"
                       class="form-control">
            </div>

            <!-- =========================
                 BOTÃO FILTRAR
                 - Submete o form via GET
            ========================== -->
            <div class="col-md-1 d-grid">
                <button class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>

            <!-- =========================
                 LIMPAR FILTROS
                 - Volta para a rota base sem querystring
            ========================== -->
            <div class="col-md-12 text-end">
                <a href="<?= site_url('estoque/relatorio') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Limpar filtros
                </a>
            </div>

        </form>

    </div>
</div>

<!-- =========================================================
     TABELA DO RELATÓRIO
     - Exibe movimentações + status de estoque
     - Inclui "Ajuste" como tipo
========================================================= -->
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
                        /* =====================================================
                           BADGE STATUS (Crítico ou OK)
                           - Baseado em estoque_atual e estoque_minimo
                        ====================================================== */
                        $statusBadge = ((int)$m['estoque_atual'] <= (int)$m['estoque_minimo'])
                            ? "<span class='badge bg-danger'>Crítico</span>"
                            : "<span class='badge bg-success'>OK</span>";

                        /* =====================================================
                           LABEL DO TIPO (Entrada / Saída / Ajuste)
                           - Agora tratamos 'ajuste'
                        ====================================================== */
                        if ($m['tipo'] === 'entrada') {
                            $tipoLabel = "<span class='badge bg-success'>
                                            <i class='bi bi-arrow-down-circle'></i> Entrada
                                          </span>";
                        } elseif ($m['tipo'] === 'saida') {
                            $tipoLabel = "<span class='badge bg-danger'>
                                            <i class='bi bi-arrow-up-circle'></i> Saída
                                          </span>";
                        } else { // ajuste
                            $tipoLabel = "<span class='badge bg-warning text-dark'>
                                            <i class='bi bi-sliders'></i> Ajuste
                                          </span>";
                        }

                        /* =====================================================
                           COR DA QUANTIDADE (QTD)
                           - Segue a mesma lógica visual dos seus cards:
                             vermelho: crítico
                             amarelo: próximo do mínimo
                             verde: ok
                        ====================================================== */
                        if ((int)$m['estoque_atual'] <= (int)$m['estoque_minimo']) {
                            $corQtd = "text-danger fw-bold";
                        } elseif ((int)$m['estoque_atual'] <= ((int)$m['estoque_minimo'] + 2)) {
                            $corQtd = "text-warning fw-bold";
                        } else {
                            $corQtd = "text-success fw-bold";
                        }
                    ?>

                    <tr>
                        <!-- Data formatada -->
                        <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>

                        <!-- Tipo com badge -->
                        <td><?= $tipoLabel ?></td>

                        <!-- Quantidade com cor -->
                        <td class="<?= $corQtd ?>">
                            <?= (int)$m['quantidade'] ?>
                        </td>

                        <!-- Dados da peça -->
                        <td>
                            <strong><?= esc($m['nome_peca']) ?></strong><br>
                            <small class="text-muted">SKU: <?= esc($m['sku']) ?></small>
                        </td>

                        <!-- Usuário (com fallback elegante) -->
                        <td>
                            <?= !empty($m['nome_usuario'])
                                ? esc($m['nome_usuario'])
                                : "<span class='text-muted fst-italic'>Sistema</span>"
                            ?>
                        </td>

                        <!-- Motivo -->
                        <td><?= esc($m['motivo']) ?></td>

                        <!-- Estoque após movimentação -->
                        <td><?= (int)$m['estoque_atual'] ?></td>

                        <!-- Status (Crítico/OK) -->
                        <td><?= $statusBadge ?></td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<!-- =========================================================
     DATATABLES
     - Ativa paginação / busca / ordenação
     - Ordem inicial: data desc (últimas primeiro)
========================================================= -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Garante que jQuery e a tabela existem antes de inicializar
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
