<?= $this->include('layouts/header') ?>

<style>
/* ============================
   ESTILO PREMIUM NORDESTINO
   ============================ */
.card-nordestina {
  position: relative;
  border-radius: 16px;
  overflow: hidden; /* para efeitos elegantes */
  background-color: #fffef7;
  border: 1px solid #e0c89f;

  /* sombras profissionais em camadas */
  box-shadow:
    0 4px 10px rgba(0,0,0,0.10),
    0 8px 20px rgba(0,0,0,0.06);

  transition: transform .35s ease, box-shadow .35s ease;
}

/* Hover elegante e moderno */
.card-nordestina:hover {
  transform: translateY(-8px);
  box-shadow:
    0 10px 22px rgba(0,0,0,0.18),
    0 18px 45px rgba(0,0,0,0.10);
}

/* Barra decorativa nordestina */
.card-nordestina::before {
  content: "";
  width: 100%;
  height: 6px;
  position: absolute;
  top: 0;
  left: 0;
  background: repeating-linear-gradient(
    45deg,
    #c58f3f,
    #c58f3f 12px,
    #944b12 12px,
    #944b12 24px
  );
  z-index: 5;
}

/* Ornamento opcional (caso queira ativar) */
/*
.card-nordestina::after {
  content: "";
  position: absolute;
  width: 60px;
  height: 60px;
  top: -20px;
  left: 20px;
  background: url('assets/img_site/ornamento.png') no-repeat center/contain;
  opacity: .8;
  z-index: 10;
}
*/

/* --------------------
   IMAGEM DO CARD
   -------------------- */
.card-img-top {
  width: 100%;
  height: 240px;
  object-fit: cover;
  object-position: center;
  border-bottom: 0;
  transform: scale(1);
  transition: transform 0.5s ease;
}

/* Leve zoom elegante no hover */
.card-nordestina:hover .card-img-top {
  transform: scale(1.05);
}

/* Overlay premium na imagem */
.card-img-top-wrapper {
  position: relative;
}

.card-img-overlay-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    180deg, 
    transparent 40%, 
    rgba(0,0,0,0.10)
  );
  border-radius: inherit;
  z-index: 2;
}

/* --------------------
   CONTEÚDO DO CARD
   -------------------- */
.card-body {
  padding: 1.2rem 1rem 1.1rem;
  background: #fffef9;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.card-title {
  color: #944b12;
  font-family: 'Merriweather', serif;
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: .4rem;
}

.card-text {
  color: #5b4b38;
  font-family: 'Poppins', sans-serif;
  font-size: .96rem;
  line-height: 1.45rem;
  flex-grow: 1;
  opacity: .85;
}

/* --------------------
   FOOTER GLASS EFFECT
   -------------------- */
.card-footer {
  padding: 12px 16px;
  background: rgba(255,255,255,0.6);
  backdrop-filter: blur(6px);
  border-top: 1px solid rgba(200,170,120,0.4);
}

.card-footer .text-warning {
  font-size: 1.3rem;
  text-shadow: 0 0 4px rgba(255,215,0,0.3);
}
</style>


<!-- ===============================
     SEÇÃO HISTÓRICA
     =============================== -->
<section id="historico" class="bg-nordestina">
  <div class="container">

    <h2 class="text-center mb-4 fw-bold text-danger" style="font-family: 'Merriweather', serif;">
      Retalhos do Caminho
    </h2>

    <p class="text-center mb-5 text-muted" style="max-width: 700px; margin: 0 auto; font-style: italic;">
      De terreiro em terreiro, de cidade em cidade, nossos projetos deixam rastro de arte e poesia.
      Conheça por onde o sertão já floresceu com a nossa cultura!
    </p>

    <div class="row g-4" data-aos="fade-up" data-aos-duration="2200">

      <?php
      // Eventos
      $historico = [
        ['img' => 'rau_seixas.png', 'titulo' => 'Casas de cultura - SMC', 'desc' => 'Ações artísticas em unidades culturais da prefeitura.'],
        ['img' => 'ceu_unificado.jpg', 'titulo' => 'Céus centro educacional unificado', 'desc' => 'Show de música e brincadeiras nordestinas. Evento livre e popular.'],
        ['img' => 'biblioteca.png', 'titulo' => 'Secretaria Municipal de Bibliotecas', 'desc' => 'Ações literárias e apresentações artísticas.'],
        ['img' => 'Entoadinha.png', 'titulo' => 'Festival Entoadinha Nordestina - São Caetano', 'desc' => 'Celebrando a cultura do sertão para o público infantil.'],
      ];

      foreach ($historico as $evento): ?>
        <div class="col-md-4" data-aos="fade-up" data-aos-duration="1200">

          <div class="card h-100 card-nordestina">

            <!-- IMG -->
            <div class="card-img-top-wrapper">
              <img src="<?= base_url('assets/img_site/' . $evento['img']) ?>" class="card-img-top" loading="lazy" alt="<?= esc($evento['titulo']) ?>">
              <div class="card-img-overlay-gradient"></div>
            </div>

            <!-- Conteúdo -->
            <div class="card-body">
              <h5 class="card-title"><?= esc($evento['titulo']) ?></h5>
              <p class="card-text"><?= esc($evento['desc']) ?></p>
            </div>

            <!-- Footer -->
            <div class="card-footer">
              <div class="text-warning">
                ⭐⭐⭐⭐⭐
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

</main>
