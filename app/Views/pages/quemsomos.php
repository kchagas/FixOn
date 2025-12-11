<?= $this->include('layouts/header') ?>
<link href="<?= base_url('assets/css/cssQuemSomos.css') ?>" rel="stylesheet">

<link href="<?= base_url('assets/css/cssQuemSomos.css') ?>" rel="stylesheet">

<!-- Botão de Voltar ao Início -->
<div class="text-center bg-light py-4 animate__animated animate__fadeInDown" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
  <a href="<?= base_url('/') ?>" 
     class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2"
     style="font-size: 1.05rem; transition: all 0.3s ease;">
    <i class="bi bi-house-door-fill" style="font-size: 1.3rem;"></i> Voltar para o Início
  </a>
</div>

<style>
.quem-somos-texto {
  position: relative;
  z-index: 1;
  background-color: rgba(255, 255, 255, 0.92);
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
  overflow: hidden;
}

/* Marca d’água como pseudo-elemento no fundo do bloco de texto */
.quem-somos-texto::before {
  content: "";
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  background: url('<?= base_url('assets/img/equipe/turma.png') ?>') center center no-repeat;
  background-size: 500px;
  background-attachment: fixed;
  opacity: 0.20; /* Ajuste para suavidade */
  z-index: 0;
}
.card:hover {
  transform: scale(1.02);
  transition: 0.3s ease;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

</style>


<!-- Fundo temático -->
<div class="bg-nordeste">
  <div class="bandeirinha animate__animated animate__fadeInDown"></div>

  <div class="container">
    
    <!-- Sobre o coletivo com marca d'água -->
    <section class="mt-5 sobre-coletivo-bg">
      <h2 class="text-center text-success fw-bold mb-4">🌾 Nossa Essência</h2>

      <div class="quem-somos-texto text-justify px-3 py-2">
        <h3 class="fw-bold text-danger text-center mb-4">Unirversos: Tecendo Sonhos e Transformando Realidades pela Cultura Infantil</h3>

        <p>Nascemos em 2018, pulsando com a energia e a visão de <strong>Johnny Brandaz</strong>, fruto de sua experiência transformadora no <strong>Programa Jovem Monitor Cultural (PJMC)</strong>. Foi nesse espaço de formação e ação que Johnny percebeu, com clareza e urgência, uma lacuna profunda: o acesso limitado à cultura de qualidade, especialmente as artes cênicas, nas <strong>periferias de São Paulo</strong>. Daí brotou a semente do <strong>Unirversos Coletivo</strong>.</p>

        <p>Nosso nome carrega nossa essência: <strong>Unir + Universos</strong>. Acreditamos no poder da arte como ponte, capaz de conectar mundos distintos, despertar a imaginação e construir novos horizontes. Nosso propósito fundamental é <strong>democratizar o acesso à cultura</strong>, levando experiências artísticas significativas e de alto nível para crianças e famílias que historicamente encontram barreiras geográficas, sociais e econômicas para vivenciá-las.</p>

        <p><strong>Nosso foco é claro e apaixonado: o público infantil.</strong> Reconhecemos nas crianças o público mais aberto, curioso e capaz de transformação. Acreditamos que o contato precoce com o teatro, a música, a dança e outras linguagens artísticas é vital para o desenvolvimento humano integral, a formação de plateias críticas e a construção de uma sociedade mais sensível e criativa.</p>

        <p>Por isso, nosso trabalho se materializa na <strong>criação e produção de inúmeros espetáculos infantis</strong>. Desenvolvemos linguagens acessíveis e envolventes, sempre com qualidade artística e sensibilidade para dialogar com a realidade e a riqueza cultural das periferias. Nossas apresentações não são apenas entretenimento; são <strong>momentos de encantamento, reflexão, descoberta e fortalecimento de identidades</strong>.</p>

        <p>Atuamos prioritariamente onde a cultura precisa chegar com mais força: <strong>nas quebradas, nos CEUs, nas escolas públicas, nos centros culturais comunitários e nos espaços alternativos das periferias paulistanas</strong>. Levamos cores, histórias, música e magia para transformar praças, salões comunitários e quadras em verdadeiros palcos de sonhos.</p>

        <p><strong>O Unirversos é mais que um coletivo artístico; é um movimento.</strong> Movimento impulsionado pela crença de que a cultura é um direito fundamental, e que o teatro infantil é uma ferramenta poderosa para semear futuros mais brilhantes, mais justos e repletos de possibilidades. Continuamos, desde 2018, com a mesma energia de Johnny e de todos que se juntaram a essa jornada, a <strong>desbravar universos e unir pessoas através da magia do teatro para crianças</strong>.</p>

        <div class="custom-divider my-5"></div>
      </div>
    </section>
  </div>
</div>


<!-- ======================================
     MISSÃO, VISÃO E VALORES - BLOCÃO PREMIUM
======================================= -->
<section id="mvv" class="py-5" style="background: #fffaf2;">
  <div class="container" data-aos="fade-up" data-aos-duration="1400">

    <!-- TÍTULO PRINCIPAL -->
    <h2 class="text-center fw-bold mb-4" 
        style="font-family: 'Merriweather', serif; color: #8c4310;">
      Nossa Essência
    </h2>

    <!-- PARÁGRAFO INTRODUTÓRIO -->
    <p class="text-center text-muted mb-5" 
       style="max-width: 780px; margin: 0 auto; font-style: italic;">
      Um caminhar que mistura cultura, arte e encantamento.  
      Aqui reunimos aquilo que guia nossos passos no sertão, na cidade  
      e em cada coração que encontra nossos projetos.
    </p>

    <!-- BLOCO ÚNICO PREMIUM -->
    <div class="card card-nordestina px-3 py-4" 
         style="border-left: 6px solid #b06a23;">

      <div class="row g-4 align-items-start">

        <!-- MISSÃO -->
        <div class="col-md-4">
          <h4 class="fw-bold mb-3" 
              style="font-family: 'Merriweather', serif; color: #944b12;">
            🎯 Nossa Missão
          </h4>

          <p style="color: #5b4b38; font-family: 'Poppins', sans-serif;">
            Levar arte, cultura e encantamento a crianças, jovens e adultos,  
            despertando a imaginação, fortalecendo raízes nordestinas e conectando  
            pessoas através da poesia, do lúdico e das histórias que transformam.
          </p>
        </div>

        <!-- VISÃO -->
        <div class="col-md-4">
          <h4 class="fw-bold mb-3" 
              style="font-family: 'Merriweather', serif; color: #944b12;">
            🌅 Nossa Visão
          </h4>

          <p style="color: #5b4b38; font-family: 'Poppins', sans-serif;">
            Ser referência nacional em arte e educação cultural,  
            inspirando cidades, escolas e projetos a valorizarem  
            o brincar, a tradição e a magia que existe dentro de cada pessoa.
          </p>
        </div>

        <!-- VALORES -->
        <div class="col-md-4">
          <h4 class="fw-bold mb-3" 
              style="font-family: 'Merriweather', serif; color: #944b12;">
            🌟 Nossos Valores
          </h4>

          <ul style="color: #5b4b38; padding-left: 18px; font-family: 'Poppins', sans-serif;">
            <li>Respeito à infância e à diversidade</li>
            <li>Tradição e modernidade caminhando juntas</li>
            <li>Arte como ferramenta de transformação</li>
            <li>Acessibilidade e inclusão</li>
            <li>Afeto, poesia e encantamento</li>
          </ul>
        </div>

      </div>
    </div>

    <!-- ======================================
         BLOCÃO DE ACESSIBILIDADE - XILOGRAVURA PREMIUM
    ======================================= -->
    <div class="mt-4 p-4"
         style="
            background: #fffdf7;
            border: 2px solid #b06a23;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            font-family: 'Poppins', sans-serif;
         ">

      <!-- Moldura xilogravura -->
      <div style="
           position:absolute;
           inset:0;
           border: 4px dashed #944b12;
           border-radius: 12px;
           pointer-events: none;
         "></div>

      <div class="d-flex align-items-start gap-3">

        <!-- ÍCONE XILOGRAVURA LIBRAS -->
        <div style="
            width: 60px; 
            height: 60px;
            background: #944b12;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.18);
        ">
          <span style="font-size: 34px; color: #fff;">🤟</span>
        </div>

        <!-- TEXTO -->
        <div style="max-width: 700px;">
          <h5 class="fw-bold mb-1" 
              style="font-family: 'Merriweather', serif; color:#944b12;">
            Acessibilidade em Libras
          </h5>
          <p style="color:#5b4b38; font-size: 0.95rem;">
            Nossas apresentações contam com intérprete de Libras, garantindo  
            inclusão, comunicação acessível e acolhimento para todas as pessoas,  
            respeitando a diversidade que fortalece a arte e a cultura.
          </p>
        </div>

      </div>
    </div>

  </div>
</section>


      
</section>

 <!-- Diversidade -->
<div class="container my-5 py-4 px-4 rounded-4 shadow-sm" style="background: linear-gradient(180deg, #fffaf2, #ffffff); border-left: 6px solid #b02a30;">
  <section>
    <h2 class="text-center fw-bold mb-4" style="color: #b02a30; font-family: 'Playfair Display', serif;">
      🤝 Compromisso com a Diversidade
    </h2>
    <p class="lead text-center px-md-5" style="color: #5e3c00; font-family: 'Georgia', serif; line-height: 1.8;">
      Aqui no <strong>Unirversos Coletivo</strong>, a arte é ponte e tambor: ecoa vozes, rompe barreiras e acolhe a <span class="text-danger fw-bold">diversidade que pulsa nas vielas, nas aldeias e nos corações vibrantes do nosso Mundo!</span>
      <br><br>
      Indígenas, LGBTQIA+, artistas da quebrada, mestres dos saberes populares e todo mundo que faz da arte seu grito e seu abrigo – <span class="fst-italic">cabem tod@s nesse terreiro de afeto e resistência</span>.
      <br><br>
      🌈 Porque espalhar arte por aí é também semear <strong>respeito, pertencimento e sonhos possíveis</strong>.
    </p>
  </section>
</div>

    </section>


    <style>
      /* Barra lateral animada crescendo */
.border-left-danger {
  border-left: 4px solid #b02a30;
  transition: border-left-width 0.3s ease;
}
.border-left-primary {
  border-left: 4px solid #0d6efd;
  transition: border-left-width 0.3s ease;
}
.border-left-success {
  border-left: 4px solid #198754;
  transition: border-left-width 0.3s ease;
}

/* Hover nos cards */
.card-hover:hover {
  background-color: #fff7f7;
  box-shadow: 0 8px 20px rgba(176, 42, 48, 0.3);
  transform: translateY(-6px) scale(1.02);
  border-left-width: 8px;
  transition: all 0.3s ease;
  cursor: pointer;
}

/* Ícones com animação de cor */
.icon-danger {
  transition: color 0.3s ease;
  color: #b02a30;
}
.icon-primary {
  transition: color 0.3s ease;
  color: #0d6efd;
}
.icon-success {
  transition: color 0.3s ease;
  color: #198754;
}

.card-hover:hover .icon-danger {
  color: #7a1417;
}
.card-hover:hover .icon-primary {
  color: #083ea1;
}
.card-hover:hover .icon-success {
  color: #0f5132;
}

    </style>

        <!-- Projetos em Destaque -->
<section class="py-5 px-3 px-md-5" style="background: linear-gradient(180deg, #fffdf8, #ffffff);">
  <h2 class="text-center fw-bold mb-5" style="color: #b02a30; font-family: 'Playfair Display', serif;">
    🎨 Projetos em Destaque
  </h2>
  <div class="row mt-4 g-4">
    <!-- Card 1 -->
    <div class="col-md-4">
      <div class="p-4 bg-light rounded shadow-sm h-100 animate__animated animate__fadeInUp card-hover border-left-danger">
        <h5 class="fw-bold text-danger d-flex align-items-center">
          <i class="bi bi-music-note-beamed me-2 icon-danger"></i>Música, Brincadeiras e Alegria
        </h5>
        <p class="text-muted mb-0" style="line-height: 1.7;">
          Uma festa arretada com <strong>dança, cantoria e riso solto</strong>. Do forró ao coco, todo mundo entra na roda pra celebrar a alegria do nosso povo.
        </p>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-md-4">
      <div class="p-4 bg-light rounded shadow-sm h-100 animate__animated animate__fadeInUp animate__delay-1s card-hover border-left-primary">
        <h5 class="fw-bold text-primary d-flex align-items-center">
          <i class="bi bi-journal-text me-2 icon-primary"></i>Sarau Nordestino
        </h5>
        <p class="text-muted mb-0" style="line-height: 1.7;">
          Cordel, poesia e talentos mirins do sertão. Um espaço onde <strong>a palavra ganha corpo, alma e sotaque</strong>. Pra rimar, sentir e se emocionar.
        </p>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col-md-4">
      <div class="p-4 bg-light rounded shadow-sm h-100 animate__animated animate__fadeInUp animate__delay-2s card-hover border-left-success">
        <h5 class="fw-bold text-success d-flex align-items-center">
          <i class="bi bi-stars me-2 icon-success"></i>Encantos do Mágico Patropiz
        </h5>
        <p class="text-muted mb-0" style="line-height: 1.7;">
          Espetáculo de <strong>magia, causos e encantamento</strong>, onde a infância vibra e o impossível vira poesia. Uma viagem lúdica pra toda a família.
        </p>
      </div>
    </div>
  </div>
</section>

 <style>
  .card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
  }

  .card-img-top {
  height: 370px;
  width: 100%;
  object-fit: cover;
  object-position: center top;
}

/* Para celular (não corta mais o rosto) */
@media (max-width: 768px) {
  .card-img-top {
    height: auto;
    max-height: 300px;
    object-fit: contain;
    object-position: center center;
    background-color: #fff; /* Previne fundo preto ao redor */
  }
}

  .card-title {
    font-size: 1.25rem;
    font-weight: 600;
  }

  .card-text.small {
    font-size: 0.9rem;
    color: #444;
    font-style: italic;
  }

  .modal-content {
    background: #fff9f5;
    border: none;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
  }

  .modal-header {
    background: #f9ece6;
    border-bottom: none;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
  }

  .modal-title {
    font-family: 'Georgia', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #7a3e3e;
  }

  .modal-body {
    font-family: 'Georgia', serif;
    font-size: 1rem;
    line-height: 1.6;
    color: #4a2d2d;
    padding-top: 0;
  }

  .btn-close {
    filter: invert(40%) sepia(10%) saturate(300%) hue-rotate(315deg);
  }

  @media (max-width: 768px) {
    .card-img-top {
      height: 200px;
    }
    .modal-body {
      font-size: 0.95rem;
    }
    .modal-title {
      font-size: 1.25rem;
    }
  }
</style>

<section id="equipe" class="py-5" style="background: linear-gradient(180deg, #fff8f0, #fefefe);">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold display-5" style="color: #b02a30; font-family: 'Playfair Display', serif;">
        🌵 Conheça Nossa Equipe Arretada 🌵
      </h2>
      <p class="lead" style="color: #5e3c00; font-family: 'Georgia', serif;">
        Um bando de artista danado de bom que espalha arte, saber e encantamento por onde passa.  
        <br>
        <span style="font-style: italic;">Do palco às oficinas, da poesia ao batuque – eis o Unirversos Coletivo.</span>
      </p>
    </div>
    <div class="row g-4">

      <!-- Card Gleice -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card shadow-sm rounded-4 h-100">
          <img src="<?= base_url('assets/img/equipe/g.png') ?>" class="card-img-top rounded-top" alt="Gleice Espíndola">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Gleice Espíndola</h5>
            <p class="card-text small">Artista da cena e das imagens. Atua entre o teatro, as artes visuais e a fotografia, com olhar sensível e político.</p>
            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill mt-auto d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalGleice">
              <i class="bi bi-person-lines-fill"></i> Continuar conhecendo
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Gleice -->
      <div class="modal fade" id="modalGleice" tabindex="-1" aria-labelledby="modalGleiceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content rounded-4">
            <div class="modal-header">
              <h5 class="modal-title" id="modalGleiceLabel">Gleice Espíndola</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Gleice Espíndola, 26, é artista da cena e das imagens: atua entre o teatro, as artes visuais e a fotografia. Moradora da Zona Leste de São Paulo, integra o coletivo Unirversos, onde desenvolve cênicas e fotografia documental.</p>
              <p>Formada em Técnico de Teatro pela ETEC de Artes, tem em seu currículo experiências com improvisação, atuação em coletivos e pesquisas voltadas a novas dramaturgias.</p>
              <p>Como atriz, participou do espetáculo Ensaio Íntimo (Sesc Consolação, 2021), e da montagem Já é Quase Meia-Noite (2023), sob direção de Priscilla Klesse, onde interpretou os personagens Jonas e Pragma.</p>
              <p>No audiovisual, trabalhou em curtas-metragens da Guaraná Filmes, além de participações em campanhas publicitárias, como figuração para a marca Piracanjuba.</p>
              <p>Como fotógrafa, atua em projetos autorais e registros documentais, principalmente de palco teatral, sempre guiada por um olhar sensível e político sobre o cotidiano.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Fabiana -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card shadow-sm rounded-4 h-100">
           <img src="<?= base_url('assets/img/equipe/Fabiana.png') ?>" class="card-img-top rounded-top" alt="Fabiana Couto">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Fabiana Couto</h5>
            <p class="card-text small">Artista educadora e oficineira que utiliza arte e simbologias ancestrais para promover paz e transformação.</p>
            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill mt-auto d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalFabiana">
              <i class="bi bi-person-lines-fill"></i> Continuar conhecendo
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Fabiana -->
      <div class="modal fade" id="modalFabiana" tabindex="-1" aria-labelledby="modalFabianaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content rounded-4">
            <div class="modal-header">
              <h5 class="modal-title" id="modalFabianaLabel">Fabiana Couto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Fabiana Couto é uma artista educadora e oficineira dedicada, formada em Artes Visuais. Sua trajetória artística e pedagógica é marcada por uma profunda conexão com a comunidade, especialmente através do coletivo Unirversos, onde atua de forma engajada.</p>
              <p>Conhecida por incorporar uma vertente xamânica em seu trabalho, Fabiana busca transcender os limites convencionais da arte e da educação. Seu propósito central é o "controle da paz" – um conceito que reflete sua missão de cultivar harmonia interior, conexão espiritual e equilíbrio nos indivíduos e no coletivo, utilizando a arte como veículo transformador.</p>
              <p>Seu impacto mais significativo se revela na grande participação no desenvolvimento de crianças. Por meio de suas oficinas criativas, permeadas por simbologias ancestrais e práticas artísticas sensíveis, Fabiana cria espaços seguros de expressão, autoconhecimento e cura.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Welisson -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card shadow-sm rounded-4 h-100">
         <img src="<?= base_url('assets/img/equipe/w.png') ?>" class="card-img-top rounded-top" alt="Wlison">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Welisson Guedes</h5>
            <p class="card-text small">Produtor cultural, músico e educador. Atua com percussão, canto e cultura popular em diversos projetos sociais e artísticos.</p>
            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill mt-auto d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalWelisson">
              <i class="bi bi-person-lines-fill"></i> Continuar conhecendo
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Welisson -->
      <div class="modal fade" id="modalWelisson" tabindex="-1" aria-labelledby="modalWelissonLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content rounded-4">
            <div class="modal-header">
              <h5 class="modal-title" id="modalWelissonLabel">Welisson Guedes</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Welisson Guedes é formado em Letras pela UNIFESP, atua como produtor cultural, músico e arte educador. Já coordenou diversos projetos culturais e ministrou oficinas em diversos equipamentos culturais e educacionais através do Programa Vocacional e Oficinas Culturais.</p>
              <p>Participou de vivências com mestres de cultura popular. Atualmente é diretor musical e produtor do Baque CT e ministra oficinas de Inglês com Música e Percussão.</p>
              <p>Possui Registro Profissional (DRT) como ator - nº 0048785/SP e Inscrição nº 71.847 na Ordem dos Músicos do Brasil (OMB).</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Johnny -->
      <div class="col-md-6 col-lg-3 d-flex">
        <div class="card shadow-sm rounded-4 h-100">
           <img src="<?= base_url('assets/img/equipe/jh.png') ?>" class="card-img-top rounded-top" alt="Johnny Brandaz">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Johnny Brandaz</h5>
            <p class="card-text small">Artista educador, músico e criador do Mágico Patropiz. Atua na formação de artistas com arte, inclusão e poesia.</p>
            <button class="btn btn-outline-danger btn-sm w-100 rounded-pill mt-auto d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalJohnny">
              <i class="bi bi-person-lines-fill"></i> Continuar conhecendo
            </button>
          </div>
        </div>
      </div>

      <!-- Modal Johnny -->
      <div class="modal fade" id="modalJohnny" tabindex="-1" aria-labelledby="modalJohnnyLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content rounded-4">
            <div class="modal-header">
              <h5 class="modal-title" id="modalJohnnyLabel">Johnny Brandaz</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Johnny Brandaz é um artista educador, professor e músico multidisciplinar, com atuação marcante em projetos culturais e pedagógicos. Formado em Letras, Teatro e Música, e pós-graduado em Educação Inclusiva e Artes, sua trajetória une linguagens artísticas e compromisso social.</p>
              <p>Atuou em programas como o PIA (Programa de Iniciação Artística) e o programa Jovem Monitor Cultural. Fundador do Unirversos Coletivo, espaço de experimentação e conexão entre arte e comunidade, Johnny também se destaca como artista ponte, colaborando com diversos grupos e movimentos culturais.</p>
              <p>Criador do Mágico Patropiz, personagem lúdico que mistura poesia, música e teatro, ele leva magia e reflexão para públicos de todas as idades. Como professor de Língua Portuguesa, alia sua paixão pela palavra à educação transformadora, sempre com um olhar inclusivo e criativo.</p>
              <p>Johnny Brandaz é, assim, um agitador cultural que transita entre palcos, salas de aula e territórios periféricos, tecendo redes de artes.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Igor -->
<div class="col-md-6 col-lg-3 d-flex">
  <div class="card shadow-sm rounded-4 h-100">
    <img src="<?= base_url('assets/img/equipe/igor.jpg') ?>" class="card-img-top rounded-top" alt="Igor Silva de Abreu">
    <div class="card-body d-flex flex-column">
      <h5 class="card-title">Igor Silva de Abreu</h5>
      <p class="card-text small">
        Minibio – Igor<br>
        Igor Silva de Abreu, 29 anos, conhecido como I.S.O (Igor Silva Olhares), é formado em edição de vídeo pelo SENAC.
      </p>
     <button class="btn btn-outline-danger btn-sm w-100 rounded-pill d-flex align-items-center justify-content-center gap-2"
        data-bs-toggle="modal" data-bs-target="#modalIgor" aria-label="Continuar conhecendo Igor Silva">
  <i class="bi bi-person-lines-fill"></i>
  <span>Continuar conhecendo</span>
</button>

    </div>
  </div>
</div>

<!-- Modal Igor -->
<div class="modal fade" id="modalIgor" tabindex="-1" aria-labelledby="modalIgorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title" id="modalIgorLabel">Igor Silva de Abreu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-justify">
        Igor está cursando o ensino superior em Fotografia e atua como fotógrafo desde 2017, com experiência em eventos sociais, culturais, ensaios artísticos e moda. Também trabalha como filmmaker, com gravação e captação de conteúdo audiovisual.
        <br><br>
        Realiza parcerias e presta serviços para coletivos, artistas independentes, espaços culturais, casas de shows e produtores artísticos. Já teve suas fotografias exibidas em três exposições coletivas e recentemente realizou uma exposição solo focada em "fotografias mobile".
        <br><br>
        Atua ainda como educador ministrando oficinas de fotografia com celulares em locais como o Centro de Formação Cultural Cidade Tiradentes, Casa de Cultura Raul Seixas e no programa Recreio nas Férias. É fotógrafo e arte-educador no Coletivo Universos.
      </div>
    </div>
  </div>
</div>


    </div>
  </div>
</section>

  </div> <!-- /container -->
</div> <!-- /bg-nordeste -->

<?= $this->include('layouts/footer') ?>
