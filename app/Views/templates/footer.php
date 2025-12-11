<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ===== Persistência de tema (localStorage) =====
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

// aplicar tema salvo ao carregar
applyTheme(savedTheme || 'light');

// toggle manual
themeToggleBtn.addEventListener('click', () => {
  const isDark = document.documentElement.classList.toggle('dark-theme');
  localStorage.setItem('fixon_theme', isDark ? 'dark' : 'light');
  applyTheme(isDark ? 'dark' : 'light');
});

// ===== SIDEBAR COLAPSAR / RESPONSIVO =====
document.getElementById('toggleSidebar').onclick = () => {
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  // mobile: alterna classe 'show' (slide-in)
  if(window.innerWidth <= 768){
    sidebar.classList.toggle('show');
    return;
  }

  sidebar.classList.toggle('sidebar-collapsed');
  content.classList.toggle('content-expanded');

  document.querySelectorAll('#sidebar .menu-text').forEach(text => {
    text.classList.toggle('hide-text');
  });
};

// ===== SUBMENUS =====
function toggleSubmenu(id){
  const el = document.getElementById(id);
  el.style.display = el.style.display === 'block' ? 'none' : 'block';
}

// fechar sidebar mobile ao clicar fora (opcional)
document.addEventListener('click', (e) => {
  const sidebar = document.getElementById('sidebar');
  if(window.innerWidth <= 768 && sidebar.classList.contains('show')){
    const isClickInside = sidebar.contains(e.target) || document.getElementById('toggleSidebar').contains(e.target);
    if(!isClickInside) sidebar.classList.remove('show');
  }
});
</script>

</body>
</html>
