<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?= $this->include('templates/header'); ?>

    <style>
        /* ----------------------------
            SIDEBAR FIXO (DESKTOP)
        -----------------------------*/
        #sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            background: var(--sidebar-bg);
            transition: width .3s ease, transform .3s ease;
            z-index: 1000;
        }

        /* Sidebar colapsado no desktop */
        #sidebar.collapsed {
            width: 70px !important;
        }

        /* ----------------------------
            CONTEÚDO PRINCIPAL
        -----------------------------*/
        #mainContent {
            margin-left: 250px;
            transition: margin-left .3s ease;
            padding: 20px;
            min-height: 100vh;
        }

        /* Conteúdo expandido ao colapsar */
        #mainContent.expanded {
            margin-left: 70px !important;
        }

        /* ----------------------------
            NAVBAR FIXA
        -----------------------------*/
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 999;
            transition: left .3s ease;
        }

        /* Navbar quando sidebar colapsa */
        .navbar-expanded {
            left: 70px !important;
        }

        /* Espaço reservado para navbar */
        body {
            padding-top: 60px;
        }

        /* ----------------------------
            MOBILE (sidebar desliza)
        -----------------------------*/
        @media (max-width: 768px) {

            #sidebar {
                transform: translateX(-100%);
                width: 250px !important;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #mainContent,
            .navbar-fixed {
                margin-left: 0 !important;
                left: 0 !important;
            }
        }
    </style>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <?= $this->include('templates/sidebar'); ?>

    <!-- NAVBAR (opcional) -->
    <?php if (file_exists(APPPATH . 'Views/templates/navbar.php')): ?>
        <div id="navbarWrapper" class="navbar-fixed">
            <?= $this->include('templates/navbar'); ?>
        </div>
    <?php endif; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <div id="mainContent" class="content-wrapper">

        <!-- TÍTULO DA PÁGINA -->
        <?php if (isset($title)): ?>
            <section class="content-header pb-1">
                <div class="container-fluid">
                    <h4 class="mb-0"><?= esc($title) ?></h4>
                </div>
            </section>
        <?php endif; ?>

        <!-- ÁREA DE CONTEÚDO -->
        <section class="content">
            <div class="container-fluid pt-2">
                <?= $this->renderSection('content'); ?>
            </div>
        </section>

    </div>

    <!-- FOOTER -->
    <?= $this->include('templates/footer'); ?>

</div>

<script>
/* Controle da sidebar */
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const navbarWrapper = document.getElementById('navbarWrapper');
const toggleBtn = document.getElementById('toggleSidebar');

toggleBtn.addEventListener('click', () => {

    // MOBILE
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('show');
        return;
    }

    // DESKTOP
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
    navbarWrapper?.classList.toggle('navbar-expanded');
});
</script>

</body>
</html>
