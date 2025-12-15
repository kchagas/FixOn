<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Cadastrar Nova Peça</h3>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <div>• <?= $error ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form action="<?= site_url('pecas/salvar') ?>" method="post" class="row g-3">

    <div class="col-md-6">
        <label class="form-label">Nome da Peça</label>
        <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control text-uppercase" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Unidade</label>
        <select name="unidade_medida" class="form-control" required>
            <option value="UN">Unidade</option>
            <option value="PC">Peça</option>
            <option value="CX">Caixa</option>
        </select>
    </div>

    <div class="col-md-12">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control"></textarea>
    </div>

    <div class="mb-3">
    <label class="form-label fw-bold">Categoria</label>

    <select name="categoria_id" class="form-select" required>
        <option value="">Selecione uma categoria</option>

        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c['id'] ?>"
                <?= old('categoria_id') == $c['id'] ? 'selected' : '' ?>>
                <?= esc($c['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div class="form-text">
        A categoria ajuda na organização, relatórios e filtros.
    </div>
</div>


    <div class="col-md-4">
        <label class="form-label">Estoque Mínimo</label>
        <input type="number" name="estoque_minimo" value="2" min="1" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Preço Custo</label>
        <input type="number" step="0.01" name="preco_custo" class="form-control" value="0.00">
    </div>

    <div class="col-md-4">
        <label class="form-label">Preço Venda</label>
        <input type="number" step="0.01" name="preco_venda" class="form-control" value="0.00">
    </div>

    <div class="col-12">
        <button class="btn btn-primary">Cadastrar Peça</button>
        <a href="<?= site_url('pecas') ?>" class="btn btn-secondary">Cancelar</a>
    </div>

</form>

<?= $this->endSection() ?>
