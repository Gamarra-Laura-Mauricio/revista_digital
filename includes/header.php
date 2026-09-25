<?php
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<header class="py-3 border-bottom bg-white sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="index.php" class="navbar-brand">
            <img src="assets/images/logo.png" alt="Logo DDP" style="height: 50px;">
        </a>
        <nav class="d-none d-lg-flex gap-4 align-items-center fw-medium">
            <a href="index.php" class="<?php echo ($pagina_actual == 'index.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Inicio</a>
            
            <!-- Enlace con ancla apuntando a la sección del index -->
            <a href="index.php#actualidad" class="text-dark text-decoration-none">Actualidad</a>
            
            <a href="reportajes.php" class="<?php echo ($pagina_actual == 'reportajes.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Reportajes</a>
            <a href="podcast.php" class="<?php echo ($pagina_actual == 'podcast.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Podcast</a>
            <a href="boletin.php" class="<?php echo ($pagina_actual == 'boletin.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Boletín NTEP</a>
            <a href="alianzas.php" class="<?php echo ($pagina_actual == 'alianzas.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Alianzas</a>
            <a href="sobre-nosotros.php" class="<?php echo ($pagina_actual == 'sobre-nosotros.php') ? 'text-danger fw-bold' : 'text-dark'; ?> text-decoration-none">Sobre D&D</a>
            <a href="contacto.php" class="btn btn-outline-dark rounded-pill px-4">Contacto</a>
        </nav>
    </div>
    <button type="button" id="sidebar-toggle" class="btn-toggle-sidebar" aria-label="Abrir menú">
    ☰
</button>
<div id="sidebar-overlay" class="sidebar-overlay"></div>
<styles>   

</header>