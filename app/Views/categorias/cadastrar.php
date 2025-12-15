<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Cadastrar Categoria</h3>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>


<!-- ERROS DE VALIDAÇÃO -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $erro): ?>
                <li><?= esc($erro) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?= site_url('categorias/salvar') ?>">


    <div class="mb-3">
        <label class="form-label fw-bold">Nome da Categoria</label>
        <input type="text"
               name="nome"
               class="form-control"
               value="<?= old('nome') ?>"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao"
                  class="form-control"
                  rows="3"><?= old('descricao') ?></textarea>
    </div>

    <button class="btn btn-primary">
        <i class="bi bi-check-circle"></i> Salvar Categoria
    </button>

    <a href="<?= site_url('categorias') ?>" class="btn btn-secondary">
        Cancelar
    </a>

</form>


<?= $this->endSection() ?>
