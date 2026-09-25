<?php
include __DIR__ . '/config/conexion.php';
include 'includes/header.php';

// Función para dar formato a la fecha tipo "Set 09, 2026"
function formatearFecha($fecha) {
    if (!$fecha) return '';
    $meses = ['Jan'=>'Ene', 'Feb'=>'Feb', 'Mar'=>'Mar', 'Apr'=>'Abr', 'May'=>'May', 'Jun'=>'Jun', 'Jul'=>'Jul', 'Aug'=>'Ago', 'Sep'=>'Set', 'Oct'=>'Oct', 'Nov'=>'Nov', 'Dec'=>'Dic'];
    $timestamp = strtotime($fecha);
    $mesIngles = date('M', $timestamp);
    $mesEsp = $meses[$mesIngles] ?? $mesIngles;
    return $mesEsp . ' ' . date('d, Y', $timestamp);
}

// 1. Reportaje destacado
$sql_principal = "SELECT * FROM reportajes ORDER BY es_destacado DESC, fecha_publicacion DESC, id DESC LIMIT 1";
$res_principal = $conexion->query($sql_principal);
$destacado = ($res_principal && $res_principal->num_rows > 0) ? $res_principal->fetch_assoc() : null;

// 2. Tres reportajes siguientes
$sql_tarjetas = "SELECT * FROM reportajes ORDER BY fecha_publicacion DESC, id DESC LIMIT 1, 3";
$res_tarjetas = $conexion->query($sql_tarjetas);

// 3. Noticias recientes
$sql_noticias = "SELECT * FROM noticias ORDER BY fecha_publicacion DESC, id DESC LIMIT 3";
$res_noticias = $conexion->query($sql_noticias);

// 4. Último Boletín NTEP
$sql_boletin = "SELECT * FROM boletines ORDER BY fecha_publicacion DESC, id DESC LIMIT 1";
$res_boletin = $conexion->query($sql_boletin);
$boletin = ($res_boletin && $res_boletin->num_rows > 0) ? $res_boletin->fetch_assoc() : null;

// 5. Podcasts
$sql_podcast = "SELECT * FROM podcasts ORDER BY fecha_publicacion DESC, id DESC LIMIT 4";
$res_podcast = $conexion->query($sql_podcast);

// 6. Videos (Se agregó LIMIT para optimizar carrusel)
$sql_videos = "SELECT * FROM videos ORDER BY fecha_publicacion DESC, id DESC LIMIT 10";
$res_videos = $conexion->query($sql_videos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDP Noticias - Portal Digital</title>
    
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- FontAwesome para Íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    
    <!-- ESTILOS PERSONALIZADOS -->
    <style>
        :root {
            --rojo-ddp: #e3000f; /* Rojo exacto basado en las capturas */
            --celeste-claro: #eff6ff;
            --texto-oscuro: #212529;
        }
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; color: #333; }
        
        .text-rojo { color: var(--rojo-ddp) !important; }
        .bg-rojo { background-color: var(--rojo-ddp) !important; }
        
        /* Navegación */
        .btn-outline-contacto { border: 1px solid #333; color: #333; border-radius: 8px; padding: 8px 24px; text-decoration: none; font-weight: 500;}
        .btn-outline-contacto:hover { background-color: #333; color: #fff; }
        .nav-link { color: #555; font-weight: 500; font-size: 0.95rem; }
        .nav-link:hover, .nav-link.active { color: var(--rojo-ddp) !important; }
        
        /* Botones Globales */
        .btn-rojo { background-color: var(--rojo-ddp); color: white; border-radius: 8px; padding: 10px 24px; text-decoration: none; font-weight: 600; border: none; transition: 0.3s; display: inline-block;}
        .btn-rojo:hover { background-color: #c00000; color: white; }
        .btn-celeste { background-color: var(--celeste-claro); color: var(--rojo-ddp); padding: 8px 24px; border-radius: 6px; text-decoration: none; font-size: 0.95rem; font-weight: 600; transition: 0.3s; }
        .btn-celeste:hover { background-color: #dbeafe; color: var(--rojo-ddp); }

        /* Tarjetas */
        .card-shadow { box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 12px; transition: transform 0.3s; border: none !important;}
        .card-shadow:hover { transform: translateY(-5px); }
        .rounded-top-custom { border-radius: 12px 12px 0 0; }
        
        /* Íconos Podcast */
        .icon-peru { width: 90px; height: 90px; background-color: var(--rojo-ddp); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 4px 10px rgba(227, 0, 15, 0.3);}
        
        /* Carrusel Especiales (Negro) */
        .card-especial { background-color: #0a0a0a; border-radius: 12px; padding: 25px; color: white; min-height: 200px; display: flex; flex-direction: column; justify-content: center; position: relative;}
        .owl-nav button { background-color: white !important; color: black !important; border-radius: 50% !important; width: 35px; height: 35px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); margin: 0 5px;}
    </style>
</head>
<body class="bg-white">

    <main class="py-4">
        <!-- Título de Sección Principal -->
        <div class="container my-4">
            <h2 class="fw-bold text-dark mb-4">Reportajes</h2>
        </div>

        <!-- Reportaje Destacado -->
<!-- Sección de Reportaje Destacado -->
        <section class="py-5" style="background-color: #f8f9fa;">
            <div class="container py-4">
                <?php if ($destacado): ?>
                    <div class="row align-items-center g-5">
                        <!-- Columna de la infografía (Imagen limpia, sin sombras ni bordes) -->
                        <div class="col-lg-6">
                            <img src="<?php echo htmlspecialchars($destacado['foto_principal'] ?: 'assets/images/bannerimg.jpg'); ?>" class="img-fluid w-100" alt="Reportaje Principal" style="border-radius: 0; box-shadow: none;">
                        </div>
                        
                        <!-- Columna del texto -->
                        <div class="col-lg-6 ps-lg-4">
                            <p class="text-dark fw-semibold mb-2" style="font-size: 0.95rem;">
                                <?php echo formatearFecha($destacado['fecha_publicacion']); ?>
                            </p>
                            
                            <!-- Título en rojo -->
                            <h2 class="mb-4" style="color: #d32f2f; font-size: 2.2rem; font-weight: 600; line-height: 1.35;">
                                <?php echo htmlspecialchars($destacado['titulo']); ?>
                            </h2>
                            
                            <p class="mb-4" style="color: #6c757d; font-size: 1.05rem; line-height: 1.7;">
                                <?php echo htmlspecialchars($destacado['resumen_corto']); ?>
                            </p>
                            
                            <a href="reportaje.php?id=<?php echo $destacado['id']; ?>" class="text-dark text-decoration-none fw-bold" style="font-size: 0.95rem;">
                                Leer &rarr;
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Grilla de Reportajes Secundarios -->
        <div class="container mb-5 pb-4 border-bottom">
            <div class="row g-4">
                <?php if ($res_tarjetas && $res_tarjetas->num_rows > 0): ?>
                    <?php while($item = $res_tarjetas->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 card-shadow">
                                <img src="<?php echo htmlspecialchars($item['foto_principal'] ?: 'assets/images/bannerimg.jpg'); ?>" class="card-img-top rounded-top-custom" alt="Reportaje" style="height: 220px; object-fit: cover;">
                                <div class="card-body d-flex flex-column p-4">
                                    <small class="text-muted mb-2"><?php echo formatearFecha($item['fecha_publicacion']); ?></small>
                                    <h5 class="card-title fw-semibold text-dark fs-5" style="line-height: 1.4;"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                                    <a href="reportaje.php?id=<?php echo $item['id']; ?>" class="mt-auto fw-bold text-rojo text-decoration-none pt-3">Leer &rarr;</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <!-- Botón Ver Todos -->
            <div class="text-center mt-5">
                <a href="#" class="btn-celeste">Ver todos</a>
            </div>
        </div>

        <!-- Noticias Recientes -->
         <section id="actualidad" class="py-5">
    <div class="container">
        <!-- Aquí va el contenido de tus noticias actuales -->
    </div>
</section>
        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="fw-bold mb-4">Noticias Recientes</h2>
                <div class="row g-4">
                    <?php if ($res_noticias && $res_noticias->num_rows > 0): ?>
                        <?php while($noticia = $res_noticias->fetch_assoc()): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100 card-shadow">
                                    <img src="<?php echo htmlspecialchars($noticia['foto'] ?: 'assets/images/bannerimg.jpg'); ?>" class="card-img-top rounded-top-custom" alt="Noticia" style="height: 220px; object-fit: cover;">
                                    <div class="card-body d-flex flex-column p-4">
                                        <small class="text-muted mb-2"><?php echo formatearFecha($noticia['fecha_publicacion']); ?></small>
                                        <h5 class="card-title fw-semibold text-dark fs-5"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                                        <a href="<?php echo htmlspecialchars($noticia['link_externo'] ?: '#'); ?>" target="_blank" class="mt-auto fw-bold text-rojo text-decoration-none pt-3">Leer &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12"><p class="text-muted">No hay noticias publicadas por el momento.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        

        <!-- Último Boletín NTEP -->
        <section class="py-5">
            <div class="container">
                <?php if ($boletin): ?>
                    <div class="row align-items-center g-5">
                        <div class="col-md-6 ps-lg-4">
                            <h2 class="fw-bold text-dark display-6 mb-4">Boletín NTEP Año <?php echo date('Y', strtotime($boletin['fecha_publicacion'])); ?></h2>
                            
                            <!-- Resumen adaptado al diseño de lista -->
                            <div class="text-secondary mb-4 fs-5" style="line-height: 1.8;">
                                <?php echo nl2br(htmlspecialchars($boletin['resumen'])); ?>
                            </div>

                            <div class="d-flex align-items-center gap-5 mt-4 mb-5">
                                <div>
                                    <h1 class="text-rojo fw-bold mb-0" style="font-size: 3.5rem;">Nº <?php echo htmlspecialchars($boletin['numero_boletin']); ?></h1>
                                    <p class="text-dark fs-5 mb-0"><?php echo date('d F', strtotime($boletin['fecha_publicacion'])); ?></p>
                                </div>
                                <div class="text-center">
                                    <a href="<?php echo htmlspecialchars($boletin['archivo_pdf']); ?>" target="_blank" class="text-rojo text-decoration-none">
                                        <i class="fas fa-download fs-1 mb-2"></i>
                                        <h5 class="fw-bold text-dark mb-0">Ver Boletín</h5>
                                    </a>
                                </div>
                            </div>
                            
                            <a href="#" class="btn-rojo">Ver Todos</a>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="<?php echo htmlspecialchars($boletin['foto_portada'] ?: 'assets/images/boletin-placeholder.jpg'); ?>" alt="Portada Boletín" class="img-fluid" style="max-height: 500px; object-fit: contain;">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Podcasts -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-5 display-6 text-dark">Podcast</h2>
        
        <div class="row g-4 mb-5 justify-content-center">
            <?php 
            if (!function_exists('obtenerPortadaPodcast')) {
                function obtenerPortadaPodcast($url) {
                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
                    if (!empty($matches[1])) {
                        return "https://img.youtube.com/vi/" . $matches[1] . "/hqdefault.jpg";
                    }
                    return null;
                }
            }

            if ($res_podcast && $res_podcast->num_rows > 0): 
                while($podcast = $res_podcast->fetch_assoc()): 
                    $url = !empty($podcast['url_embed']) ? $podcast['url_embed'] : '#';
                    $portada = obtenerPortadaPodcast($url);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="px-2">
                        <!-- Contenedor clickeable -->
                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="d-inline-block text-decoration-none mb-3">
                            <?php if ($portada): ?>
                                <!-- Imagen YouTube simétrica -->
                                <img src="<?php echo $portada; ?>" 
                                     alt="<?php echo htmlspecialchars($podcast['titulo']); ?>" 
                                     class="shadow-sm mx-auto d-block" 
                                     style="width: 180px; height: 180px; object-fit: cover; border-radius: 15px;">
                            <?php else: ?>
                                <!-- Caja roja de respaldo con exactamente el mismo tamaño (180x180) -->
                                <div class="bg-danger shadow-sm mx-auto d-flex align-items-center justify-content-center" 
                                     style="width: 180px; height: 180px; border-radius: 15px;">
                                    <i class="fas fa-map-marked-alt text-white fs-1"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Título clickeable -->
                        <p class="text-secondary fs-6" style="line-height: 1.4;">
                            <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="text-secondary text-decoration-none">
                                <?php echo htmlspecialchars($podcast['titulo']); ?>
                            </a>
                        </p>
                    </div>
                </div>
            <?php 
                endwhile; 
            else: 
            ?>
                <div class="col-12">
                    <p class="text-muted">No hay podcasts disponibles en este momento.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <a href="about.html" class="btn-rojo">Ver Todos</a>
    </div>
</section>

        <!-- Especiales (Videos) -->
        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="fw-bold mb-5 text-center display-6 text-dark">Especiales</h2>
                <div class="owl-carousel owl-theme especial-carousel">
                    <?php if ($res_videos && $res_videos->num_rows > 0): ?>
                        <?php while($video = $res_videos->fetch_assoc()): ?>
                            <div class="item">
                                <a href="<?php echo htmlspecialchars($video['url_embed']); ?>" target="_blank" class="text-decoration-none">
                                    <div class="card-especial shadow-sm">
                                        <h5 class="fw-bold text-white m-0 text-center lh-base">
                                            <!-- Aquí se asume que el título viene de BD, el diseño muestra palabras resaltadas en rojo -->
                                            <?php echo htmlspecialchars($video['titulo']); ?>
                                        </h5>
                                    </div>
                                    <p class="text-secondary mt-3 text-center small px-2"><?php echo htmlspecialchars($video['titulo']); ?></p>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Sección Sobre Nosotros -->
        <!-- Sección Sobre Nosotros -->
        <section class="py-5 my-4 bg-white overflow-hidden">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0 ps-lg-4">
                        <small class="text-uppercase text-secondary fw-semibold mb-2 d-block" style="letter-spacing: 1px;">DDP NOTICIAS</small>
                        <h2 class="display-4 fw-bold text-dark mt-1 mb-4" style="line-height: 1.1;">Diálogo y Desarrollo Perú</h2>
                        <p class="text-secondary mb-5 fs-5 pe-lg-4" style="line-height: 1.6;">
                            Somos un espacio de periodismo independiente que busca visibilizar las acciones de diálogo en el país desde una mirada constructiva.
                        </p>
                        <a href="sobre-nosotros.php" class="btn-rojo">Nosotros</a>
                    </div>
                    <!-- Imagen con recorte especial en el lado derecho y sombra hacia la derecha -->
                    <div class="col-lg-7 position-relative pe-lg-4">
                        <img src="assets/images/bannerimg.jpg" alt="Diálogo y Desarrollo Perú" class="img-fluid" style="height: 480px; width: 100%; object-fit: cover; border-radius: 0 250px 250px 0; box-shadow: 10px 0 40px rgba(0,0,0,0.08);" onerror="this.src='https://via.placeholder.com/800x480'">
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Banner Redes Sociales -->
    <section class="position-relative text-white text-center py-5" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('assets/images/bg-redes.jpg') center/cover fixed;">
        <div class="container py-5">
            <h3 class="fw-bold fs-2 mb-4">Síguenos en nuestras Redes Sociales</h3>
            <div class="d-flex justify-content-center gap-4 fs-2">
                <a href="#" target="_blank" class="text-rojo text-decoration-none"><i class="fab fa-facebook"></i></a>
                <a href="#" target="_blank" class="text-rojo text-decoration-none"><i class="fab fa-tiktok"></i></a>
                <a href="#" target="_blank" class="text-rojo text-decoration-none"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </section>

<!-- INCLUIR FOOTER -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $(".especial-carousel").owlCarousel({
                loop: false,
                margin: 25,
                nav: true,
                navText: [
                    "<i class='fas fa-chevron-left'></i>",
                    "<i class='fas fa-chevron-right'></i>"
                ],
                dots: false,
                responsive: {
                    0: { items: 1 },
                    600: { items: 2 },
                    1000: { items: 4 }
                }
            });
        });
    </script>
</body>
</html>