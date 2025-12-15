<?= $this->extend('templates/dashboard') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Editar Categoria</h3>

<form method="post" action="<?= site_url('categorias/atualizar/'.$categoria['id']) ?>">

    <div class="mb-3">
        <label class="form-label">Nome *</label>
        <input type="text"
               name="nome"
               class="form-control"
               value="<?= esc($categoria['nome']) ?>"
               required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao"
                  class="form-control"
                  rows="3"><?= esc($categoria['descricao']) ?></textarea>
    </div>

    <button class="btn btn-success">
        <i class="bi bi-check-circle"></i> Atualizar
    </button>

    <a href="<?= site_url('categorias') ?>" class="btn btn-secondary">
        Voltar
    </a>
</form>

<?= $this->endSection() ?>
