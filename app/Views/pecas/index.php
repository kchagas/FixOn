<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<?php
/**
 * ============================================================
 * MODAL DE ERRO – EXCLUSÃO BLOQUEADA
 * ============================================================
 */
?>
<?php if (session()->getFlashdata('erro_exclusao')): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const html = `
    <div class="modal fade" id="erroModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
                <div class="modal-body text-center p-5">
                    <i class="bi bi-exclamation-circle text-danger fs-1 mb-3"></i>
                    <h4 class="fw-bold">Operação não permitida</h4>
                    <p class="text-muted mt-3">
                        Esta peça possui <strong>movimentações registradas</strong> e
                        não pode ser excluída para manter a integridade do histórico.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button class="btn btn-dark px-4" data-bs-dismiss="modal">Entendi</button>
                </div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', html);
    new bootstrap.Modal(document.getElementById('erroModal')).show();
});
</script>
<?php endif; ?>

<!-- ============================================================
     TOPO DA PÁGINA
============================================================ -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0">Peças</h3>
        <small class="text-muted">Peças cadastradas no sistema</small>
    </div>

    <a href="<?= site_url('pecas/cadastrar') ?>" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Nova Peça
    </a>
</div>

<!-- ============================================================
     FILTROS
============================================================ -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="get" action="<?= site_url('pecas') ?>" class="row g-3 align-items-end">

            <!-- CATEGORIA -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-tags"></i> Categoria
                </label>
                <select name="categoria_id" class="form-select">
                    <option value="">Todas as categorias</option>
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= ($filtro_categoria == $c['id']) ? 'selected' : '' ?>>
                            <?= esc($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- BUSCA -->
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-search"></i> Buscar
                </label>
                <input type="text"
                       name="q"
                       value="<?= esc($busca ?? '') ?>"
                       class="form-control"
                       placeholder="Digite SKU ou nome da peça">
            </div>

            <!-- BOTÕES -->
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
            </div>

            <?php if (!empty($filtro_categoria) || !empty($busca)): ?>
            <div class="col-md-2">
                <a href="<?= site_url('pecas') ?>" class="btn btn-outline-secondary w-100">
                    Limpar
                </a>
            </div>
            <?php endif; ?>

        </form>
    </div>
</div>

<!-- ============================================================
     TABELA
============================================================ -->
<div class="card shadow-sm">
    <div class="card-body p-0">

        <table class="table table-dark table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Estoque</th>
                    <th>Mínimo</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($pecas as $p): ?>

                <?php
                /**
                 * ==================================================
                 * STATUS LÓGICO (INTELIGÊNCIA)
                 * ==================================================
                 */
                if ($p['estoque_atual'] == 0) {
                    $statusLogico = 'zerado';
                } elseif ($p['estoque_atual'] <= $p['estoque_minimo']) {
                    $statusLogico = 'critico';
                } elseif ($p['estoque_atual'] <= $p['estoque_minimo'] + 2) {
                    $statusLogico = 'atencao';
                } else {
                    $statusLogico = 'ok';
                }

                /**
                 * STATUS VISUAL
                 */
                switch ($statusLogico) {
                    case 'zerado':
                        $statusBadge = "<span class='badge bg-danger'>Zerado</span>";
                        break;
                    case 'critico':
                        $statusBadge = "<span class='badge bg-danger'>Crítico</span>";
                        break;
                    case 'atencao':
                        $statusBadge = "<span class='badge bg-warning text-dark'>Atenção</span>";
                        break;
                    default:
                        $statusBadge = "<span class='badge bg-success'>OK</span>";
                }
                ?>

                <tr>
                    <td><?= esc($p['sku']) ?></td>

                    <td>
                        <a href="<?= site_url('pecas/detalhes/'.$p['id']) ?>"
                           class="fw-bold text-info text-decoration-none">
                            <?= esc($p['nome']) ?>
                        </a>
                    </td>

                    <td>
                        <?php if (!empty($p['categoria_nome'])): ?>
                            <span class="badge bg-secondary"><?= esc($p['categoria_nome']) ?></span>
                        <?php else: ?>
                            <span class="text-muted fst-italic">Sem categoria</span>
                        <?php endif; ?>
                    </td>

                    <td><?= $p['estoque_atual'] ?></td>
                    <td><?= $p['estoque_minimo'] ?></td>
                    <td><?= $statusBadge ?></td>

                    <!-- ==================================================
                         AÇÕES INTELIGENTES
                    ================================================== -->
                    <td class="text-end">

                        <a href="<?= site_url('pecas/editar/'.$p['id']) ?>"
                           class="btn btn-sm btn-primary"
                           title="Editar">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <?php if ($statusLogico === 'zerado'): ?>
                            <a href="<?= site_url('estoque/repor/' . $p['id'] . '?urgente=1') ?>"
                                class="btn btn-sm btn-danger"
                                title="Repor estoque urgente">
                                    <i class="bi bi-exclamation-octagon"></i>
                                </a>

                        <?php endif; ?>

                        <?php if ($statusLogico === 'critico'): ?>
                           <a href="<?= site_url('estoque/repor/' . $p['id']) ?>"
                                class="btn btn-sm btn-warning"
                                title="Repor estoque">
                                    <i class="bi bi-box-arrow-in-down"></i>
                                </a>

                        <?php endif; ?>

                        <?php if ($statusLogico === 'atencao'): ?>
                            <a href="<?= site_url('pecas/editar/'.$p['id']) ?>"
                               class="btn btn-sm btn-outline-warning"
                               title="Ajustar mínimo">
                                <i class="bi bi-sliders"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (session()->get('user_role') === 'admin'): ?>
                            <a href="<?= site_url('pecas/excluir/'.$p['id']) ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Deseja excluir esta peça?')"
                               title="Excluir">
                                <i class="bi bi-trash"></i>
                            </a>
                        <?php endif; ?>

                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>

<?= $this->endSection() ?>
