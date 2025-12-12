<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Editar Peça</h3>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div>• <?= $error ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('pecas/atualizar/' . $peca['id']) ?>" method="post" class="row g-3">

    <div class="col-md-6">
        <label class="form-label">Nome da Peça</label>
        <input type="text" name="nome" class="form-control"
               value="<?= esc($peca['nome']) ?>" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control text-uppercase"
               value="<?= esc($peca['sku']) ?>" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Unidade</label>
        <select name="unidade_medida" class="form-control" required>
            <option value="UN" <?= $peca['unidade_medida'] == 'UN' ? 'selected' : '' ?>>Unidade</option>
            <option value="PC" <?= $peca['unidade_medida'] == 'PC' ? 'selected' : '' ?>>Peça</option>
            <option value="CX" <?= $peca['unidade_medida'] == 'CX' ? 'selected' : '' ?>>Caixa</option>
        </select>
    </div>

    <div class="col-md-12">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control"><?= esc($peca['descricao']) ?></textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Estoque Mínimo</label>
        <input type="number" name="estoque_minimo" value="<?= esc($peca['estoque_minimo']) ?>"
               min="1" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Preço Custo</label>
        <input type="number" step="0.01" name="preco_custo" class="form-control"
               value="<?= esc($peca['preco_custo']) ?>">
    </div>

    <div class="col-md-4">
        <label class="form-label">Preço Venda</label>
        <input type="number" step="0.01" name="preco_venda" class="form-control"
               value="<?= esc($peca['preco_venda']) ?>">
    </div>

    <div class="col-12">
        <button class="btn btn-primary">Salvar Alterações</button>
        <a href="<?= site_url('pecas') ?>" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<?= $this->endSection() ?>
