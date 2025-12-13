<!-- ============================
      SCRIPTS ESSENCIAIS
=============================== -->

<!-- jQuery (OBRIGATÓRIO para DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables + Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el, {
            html: true,
            sanitize: false,
            placement: 'top',
            trigger: 'hover'
        });
    });

});
</script>



<!-- ============================
      ATIVAÇÃO DO DATATABLES
=============================== -->
<script>
$(document).ready(function () {

    console.log("DataTable está inicializando...");

    $('#tabelaPecas').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
    });
});
</script>

<!-- ============================
      SISTEMA DE TEMA (Dark / Light)
=============================== -->
<script>
const themeToggleBtn = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const savedTheme = localStorage.getItem('fixon_theme');

function applyTheme(theme) {
  if(theme === 'dark'){
    document.documentElement.classList.add('dark-theme');
    themeIcon.className = 'bi bi-moon-stars-fill';
  } else {
    document.documentElement.classList.remove('dark-theme');
    themeIcon.className = 'bi bi-brightness-high';
  }
}

// aplicar tema salvo
applyTheme(savedTheme || 'light');

// alternar tema manualmente
themeToggleBtn.addEventListener('click', () => {
  const isDark = document.documentElement.classList.toggle('dark-theme');
  localStorage.setItem('fixon_theme', isDark ? 'dark' : 'light');
  applyTheme(isDark ? 'dark' : 'light');
});
</script>

<!-- ============================
      SIDEBAR COLAPSÁVEL
=============================== -->
<script>
document.getElementById('toggleSidebar').onclick = () => {
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  // mobile
  if(window.innerWidth <= 768){
    sidebar.classList.toggle('show');
    return;
  }

  // desktop
  sidebar.classList.toggle('sidebar-collapsed');
  content.classList.toggle('content-expanded');

  document.querySelectorAll('#sidebar .menu-text').forEach(text => {
    text.classList.toggle('hide-text');
  });
};

// submenus
function toggleSubmenu(id){
  const el = document.getElementById(id);
  el.style.display = el.style.display === 'block' ? 'none' : 'block';
}

// esconder sidebar no mobile ao clicar fora
document.addEventListener('click', (e) => {
  const sidebar = document.getElementById('sidebar');
  if(window.innerWidth <= 768 && sidebar.classList.contains('show')){
    const isClickInside = sidebar.contains(e.target) || 
                          document.getElementById('toggleSidebar').contains(e.target);
    if(!isClickInside) sidebar.classList.remove('show');
  }
});
</script>

</body>
</html>
