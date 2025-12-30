<div id="sidebar">

    <!-- ===============================
         CONFIGURAÇÕES DO SISTEMA
    ================================ -->
    <div class="menu-item" onclick="toggleSubmenu('submenuConfig')">
        <i class="bi bi-gear-fill"></i>
        <span class="menu-text">Configurações</span>
    </div>

    <div id="submenuConfig" class="submenu">
        <a href="#">
            <i class="bi bi-people"></i> Usuários
        </a>
        <a href="#">
            <i class="bi bi-shield-lock"></i> Permissões
        </a>
    </div>


    <!-- ===============================
         ORDENS DE SERVIÇO
    ================================ -->
    <div class="menu-item" onclick="toggleSubmenu('submenuOS')">
        <i class="bi bi-file-earmark-text-fill"></i>
        <span class="menu-text">Ordens de Serviço</span>
    </div>

    <div id="submenuOS" class="submenu">
        <a href="#">
            <i class="bi bi-plus-circle"></i> Criar OS
        </a>
        <a href="#">
            <i class="bi bi-list-check"></i> Listar OS
        </a>
    </div>


    <!-- ===============================
         ESTOQUE
    ================================ -->
    <div class="menu-item" onclick="toggleSubmenu('submenuEstoque')">
        <i class="bi bi-box-seam"></i>
        <span class="menu-text">Estoque</span>
    </div>

    <div id="submenuEstoque" class="submenu">

     <!-- ANÁLISES -->
        <a href="<?= site_url('estoque/dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard do Estoque
        </a>

        <!-- CADASTROS -->
        <a href="<?= site_url('categorias') ?>">
            <i class="bi bi-tags"></i> Categorias
        </a>

        <a href="<?= site_url('pecas') ?>">
            <i class="bi bi-grid"></i> Produtos
        </a>

        <a href="<?= site_url('pecas/cadastrar') ?>">
            <i class="bi bi-plus-circle"></i> Cadastrar Produto
        </a>

        <hr class="submenu-divider">

        <!-- OPERAÇÕES -->
        <a href="<?= site_url('movimentacoes/entrada') ?>">
            <i class="bi bi-arrow-down-circle"></i> Entrada de Estoque
        </a>

        <a href="<?= site_url('movimentacoes/saida') ?>">
            <i class="bi bi-arrow-up-circle"></i> Saída de Estoque
        </a>

        <hr class="submenu-divider">

       

        <a href="<?= site_url('estoque/relatorio') ?>">
            <i class="bi bi-clipboard-data"></i> Relatório de Movimentações
        </a>

        <hr class="submenu-divider">

        <a href="<?= site_url('estoque/alertas') ?>">
            <i class="bi bi-bell"></i> Alertas Inteligentes
        </a>

        <a href="<?= site_url('estoque/compras') ?>">
            <i class="bi bi-cart-check"></i> Compras Inteligentes
        </a>

        <a href="<?= site_url('estoque/gerar-alertas') ?>">
            <i class="bi bi-arrow-repeat"></i> Atualizar Alertas
        </a>

    </div>


    <!-- ===============================
     FINANCEIRO
=============================== -->
<div class="menu-item" onclick="toggleSubmenu('submenuFinanceiro')">
    <i class="bi bi-cash-coin"></i>
    <span class="menu-text">Financeiro</span>
</div>

<div id="submenuFinanceiro" class="submenu">

    <!-- DASHBOARD FINANCEIRO -->
    <a href="<?= site_url('financeiro/dashboard') ?>">
        <i class="bi bi-speedometer2"></i> Dashboard Financeiro
    </a>

    <!-- CONTAS A PAGAR -->
    <a href="<?= site_url('financeiro/contas-pagar') ?>">
        <i class="bi bi-arrow-down-circle"></i> Contas a Pagar
    </a>

</div>


</div> <!-- FIM SIDEBAR -->
