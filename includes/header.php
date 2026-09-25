<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<header class="py-3 border-bottom bg-white sticky-top shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index.php" class="navbar-brand" aria-label="Inicio">
            <img src="assets/images/logo.png" alt="Logo DDP" style="height: 50px;">
        </a>
        
        <!-- Navegación de Escritorio -->
        <nav class="d-none d-lg-flex align-items-center fw-medium custom-gap-4">
            <a href="index.php" class="<?php echo ($pagina_actual == 'index.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none hover-text-danger transition-color" <?php echo ($pagina_actual == 'index.php') ? 'aria-current="page"' : ''; ?>>Inicio</a>
            
            <a href="index.php#actualidad" class="text-dark text-decoration-none hover-text-danger transition-color">Actualidad</a>
            
            <a href="reportajes.php" class="<?php echo ($pagina_actual == 'reportajes.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none hover-text-danger transition-color" <?php echo ($pagina_actual == 'reportajes.php') ? 'aria-current="page"' : ''; ?>>Reportajes</a>
            
            <a href="podcast.php" class="<?php echo ($pagina_actual == 'podcast.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none hover-text-danger transition-color" <?php echo ($pagina_actual == 'podcast.php') ? 'aria-current="page"' : ''; ?>>Podcast</a>
            
            <a href="boletin.php" class="<?php echo ($pagina_actual == 'boletin.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none hover-text-danger transition-color" <?php echo ($pagina_actual == 'boletin.php') ? 'aria-current="page"' : ''; ?>>Boletín NTEP</a>
            
            <a href="alianzas.php" class="<?php echo ($pagina_actual == 'alianzas.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none hover-text-danger transition-color" <?php echo ($pagina_actual == 'alianzas.php') ? 'aria-current="page"' : ''; ?>>Alianzas</a>
            
            <a href="sobre-nosotros.php" class="<?php echo ($pagina_actual == 'sobre-nosotros.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none hover-text-danger transition-color" <?php echo ($pagina_actual == 'sobre-nosotros.php') ? 'aria-current="page"' : ''; ?>>Sobre D&D</a>
            
            <a href="contacto.php" class="btn btn-outline-dark rounded-pill px-4 ms-2">Contacto</a>
        </nav>

        <!-- Botón Menú Móvil -->
        <button type="button" id="sidebar-toggle" class="btn btn-light d-lg-none border" aria-label="Abrir menú móvil">
            <span class="fs-4">☰</span>
        </button>
    </div>

    <!-- Menú Lateral (Sidebar) para Móviles -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    <div id="mobile-sidebar" class="mobile-sidebar bg-white">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <img src="assets/images/logo.png" alt="Logo DDP" style="height: 40px;">
            <button id="close-sidebar" class="btn btn-light border" aria-label="Cerrar menú">✖</button>
        </div>
        <nav class="d-flex flex-column p-4 custom-gap-3">
            <a href="index.php" class="<?php echo ($pagina_actual == 'index.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Inicio</a>
            <a href="index.php#actualidad" class="text-dark text-decoration-none">Actualidad</a>
            <a href="reportajes.php" class="<?php echo ($pagina_actual == 'reportajes.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Reportajes</a>
            <a href="podcast.php" class="<?php echo ($pagina_actual == 'podcast.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Podcast</a>
            <a href="boletin.php" class="<?php echo ($pagina_actual == 'boletin.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Boletín NTEP</a>
            <a href="alianzas.php" class="<?php echo ($pagina_actual == 'alianzas.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Alianzas</a>
            <a href="sobre-nosotros.php" class="<?php echo ($pagina_actual == 'sobre-nosotros.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Sobre D&D</a>
            <a href="contacto.php" class="btn btn-outline-dark rounded-pill px-4 mt-3">Contacto</a>
        </nav>
    </div>
</header>

<script>
    // Lógica para abrir y cerrar el sidebar en móviles
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebar-toggle');
        const closeBtn = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('mobile-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Evita el scroll trasero
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