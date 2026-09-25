<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<header class="site-header">

    <div class="header-container">

        <!-- LOGO -->
        <a href="index.php" class="site-logo" aria-label="Inicio">
            <img src="assets/images/logo.png" alt="DDP Noticias">
        </a>

        <!-- NAVEGACIÓN ESCRITORIO -->
        <nav class="desktop-nav">

            <a href="index.php"
               class="<?= ($pagina_actual == 'index.php') ? 'active' : ''; ?>">
                Inicio
            </a>

            <a href="index.php#actualidad">
                Actualidad
            </a>

            <a href="reportajes.php"
               class="<?= ($pagina_actual == 'reportajes.php') ? 'active' : ''; ?>">
                Reportajes
            </a>

            <a href="podcast.php"
               class="<?= ($pagina_actual == 'podcast.php') ? 'active' : ''; ?>">
                Podcast
            </a>

            <a href="boletin.php"
               class="<?= ($pagina_actual == 'boletin.php') ? 'active' : ''; ?>">
                Boletín NTEP
            </a>

            <a href="alianzas.php"
               class="<?= ($pagina_actual == 'alianzas.php') ? 'active' : ''; ?>">
                Alianzas
            </a>

            <a href="sobre-nosotros.php"
               class="<?= ($pagina_actual == 'sobre-nosotros.php') ? 'active' : ''; ?>">
                Sobre D&D
            </a>

            <a href="contacto.php" class="contact-btn">
                Contacto
            </a>

        </nav>

        <!-- BOTÓN MÓVIL -->
        <button
            type="button"
            id="sidebar-toggle"
            class="mobile-menu-btn"
            aria-label="Abrir menú">
            ☰
        </button>

    </div>

    <!-- OVERLAY -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- SIDEBAR MÓVIL -->
    <aside id="mobile-sidebar" class="mobile-sidebar">

        <div class="mobile-sidebar-header">

            <img src="assets/images/logo.png" alt="DDP Noticias">

            <button
                type="button"
                id="close-sidebar"
                class="close-sidebar"
                aria-label="Cerrar menú">
                ×
            </button>

        </div>

        <nav class="mobile-nav">

            <a href="index.php"
               class="<?= ($pagina_actual == 'index.php') ? 'active' : ''; ?>">
                Inicio
            </a>

            <a href="index.php#actualidad">
                Actualidad
            </a>

            <a href="reportajes.php"
               class="<?= ($pagina_actual == 'reportajes.php') ? 'active' : ''; ?>">
                Reportajes
            </a>

            <a href="podcast.php"
               class="<?= ($pagina_actual == 'podcast.php') ? 'active' : ''; ?>">
                Podcast
            </a>

            <a href="boletin.php"
               class="<?= ($pagina_actual == 'boletin.php') ? 'active' : ''; ?>">
                Boletín NTEP
            </a>

            <a href="alianzas.php"
               class="<?= ($pagina_actual == 'alianzas.php') ? 'active' : ''; ?>">
                Alianzas
            </a>

            <a href="sobre-nosotros.php"
               class="<?= ($pagina_actual == 'sobre-nosotros.php') ? 'active' : ''; ?>">
                Sobre D&D
            </a>

            <a href="contacto.php" class="mobile-contact-btn">
                Contacto
            </a>

        </nav>

    </aside>

</header>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

});
</script>