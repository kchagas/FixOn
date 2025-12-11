<div id="sidebar">
    <!-- CONFIGURAÇÕES -->
    <div class="menu-item" onclick="toggleSubmenu('submenuConfig')">
        <i class="bi bi-gear-fill"></i>
        <span class="menu-text">Configurações</span>
    </div>
    <div id="submenuConfig" class="submenu">
        <a href="#"><i class="bi bi-people"></i> Usuários</a>
        <a href="#"><i class="bi bi-shield-lock"></i> Permissões</a>
    </div>

    <!-- ORDENS DE SERVIÇO -->
    <div class="menu-item" onclick="toggleSubmenu('submenuOS')">
        <i class="bi bi-file-earmark-text-fill"></i>
        <span class="menu-text">Ordens de Serviço</span>
    </div>
    <div id="submenuOS" class="submenu">
        <a href="#"><i class="bi bi-plus-circle"></i> Criar OS</a>
        <a href="#"><i class="bi bi-list-check"></i> Listar OS</a>
    </div>

    <!-- ESTOQUE -->
    <div class="menu-item" onclick="toggleSubmenu('submenuEstoque')">
        <i class="bi bi-box-seam"></i>
        <span class="menu-text">Estoque</span>
    </div>
    <div id="submenuEstoque" class="submenu">
        <a href="#"><i class="bi bi-grid"></i> Produtos</a>
        <a href="#"><i class="bi bi-arrow-left-right"></i> Entradas/Saídas</a>
    </div>
</div>
