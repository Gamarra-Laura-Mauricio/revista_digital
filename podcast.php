<?php
include __DIR__ . '/config/conexion.php';
include 'includes/header.php';

// Formateador de fecha en español
function formatearFechaPodcast($fecha) {
    if (empty($fecha)) return '';
    $timestamp = strtotime($fecha);
    $meses = [
        1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
        5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
        9 => 'Set', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
    ];
    return $meses[(int)date('n', $timestamp)] . ' ' . date('d', $timestamp) . ', ' . date('Y', $timestamp);
}

// Función helper para extraer ID de YouTube y obtener miniatura/embed
function obtenerYoutubeId($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return false;
}

// Obtener todos los podcasts ordenados por fecha de publicación
$sql_podcasts = "SELECT * FROM podcasts ORDER BY fecha_publicacion DESC";
$res_podcasts = $conexion->query($sql_podcasts);

$podcasts = [];
if ($res_podcasts && $res_podcasts->num_rows > 0) {
    while ($row = $res_podcasts->fetch_assoc()) {
        $podcasts[] = $row;
    }
}

// Separar el episodio más reciente (destacado) del resto
$destacado = !empty($podcasts) ? array_shift($podcasts) : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcast - Diálogo y Desarrollo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-starter.css">

    <style>
        .podcast-card-img-wrapper {
            position: relative;
            overflow: hidden;
            background-color: #1a1a1a;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .podcast-card-img-wrapper img {
            transition: transform 0.4s ease, opacity 0.3s ease;
        }
        .podcast-card:hover .podcast-card-img-wrapper img {
            transform: scale(1.05);
            opacity: 0.85;
        }
        .play-overlay-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 55px;
            height: 55px;
            background-color: rgba(220, 53, 69, 0.9);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .podcast-card:hover .play-overlay-btn {
            transform: translate(-50%, -50%) scale(1.15);
            background-color: #dc3545;
        }
        .ratio-16x9-custom {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
        }
        .ratio-16x9-custom iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 1rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">


    <main class="py-5">
        <div class="container">

            <!-- Encabezado de la página -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="fw-bold text-dark m-0 fs-2">Podcast D&D</h1>
                    <p class="text-muted small m-0 mt-1">Escucha y analiza nuestros últimos episodios sobre diálogo y desarrollo regional</p>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-secondary text-decoration-none">Inicio</a></li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">Podcast</li>
                    </ol>
                </nav>
            </div>

            <?php if ($destacado): 
                $yt_id_destacado = obtenerYoutubeId($destacado['url_embed']);
                $fecha_destacado = formatearFechaPodcast($destacado['fecha_publicacion']);
                $url_destacado   = htmlspecialchars($destacado['url_embed']);
            ?>
                <!-- EPISODIO DESTACADO (ÚLTIMO LANZAMIENTO) -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-dark text-white">
                    <div class="row g-0 align-items-center">
                        <div class="col-lg-7 p-3 p-md-4">
                            <?php if ($yt_id_destacado): ?>
                                <div class="ratio-16x9-custom shadow rounded-4 overflow-hidden">
                                    <iframe src="https://www.youtube.com/embed/<?php echo $yt_id_destacado; ?>" 
                                            title="<?php echo htmlspecialchars($destacado['titulo']); ?>" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                </div>
                            <?php else: ?>
                                <a href="<?php echo $url_destacado; ?>" target="_blank" class="d-block position-relative rounded-4 overflow-hidden">
                                    <img src="assets/images/no_image.jpeg" class="img-fluid w-100 rounded-4" style="max-height: 360px; object-fit: cover;" alt="Podcast Destacado">
                                    <div class="play-overlay-btn"><i class="fas fa-play fs-4 ps-1"></i></div>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-5 p-4 p-md-5 d-flex flex-column justify-content-center">
                            <div>
                                <span class="badge bg-danger text-uppercase px-3 py-2 rounded-pill mb-3">
                                    <i class="fas fa-podcast me-1"></i> Último Episodio
                                </span>
                                <h2 class="fw-bold mb-3 text-white lh-base fs-3">
                                    <?php echo htmlspecialchars($destacado['titulo']); ?>
                                </h2>
                                <p class="text-secondary small mb-4">
                                    <i class="far fa-calendar-alt me-2"></i> <?php echo $fecha_destacado; ?>
                                </p>
                            </div>
                            <div>
                                <a href="<?php echo $url_destacado; ?>" target="_blank" class="btn btn-danger btn-lg rounded-pill px-4 py-2 fs-6 d-inline-flex align-items-center gap-2 shadow">
                                    <i class="fas fa-external-link-alt"></i> Ver / Escuchar Completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- GRILLA DE EPISODIOS ANTERIORES -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold text-dark m-0">Todos los Episodios</h4>
            </div>

            <div class="row g-4">
                <?php if (!empty($podcasts)): ?>
                    <?php foreach ($podcasts as $item): 
                        $yt_id = obtenerYoutubeId($item['url_embed']);
                        $fecha_item = formatearFechaPodcast($item['fecha_publicacion']);
                        $url_item = htmlspecialchars($item['url_embed']);
                        
                        // Si es YouTube genera la miniatura HD automáticamente; si no, usa la foto por defecto
                        $img_thumb = $yt_id ? "https://img.youtube.com/vi/{$yt_id}/hqdefault.jpg" : "assets/images/no_image.jpeg";
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white podcast-card transition-hover">
                                
                                <!-- Imagen / Miniatura enlazada -->
                                <a href="<?php echo $url_item; ?>" target="_blank" class="podcast-card-img-wrapper d-block text-decoration-none">
                                    <img src="<?php echo $img_thumb; ?>" 
                                         alt="<?php echo htmlspecialchars($item['titulo']); ?>" 
                                         class="w-100" 
                                         style="height: 220px; object-fit: cover;">
                                    <div class="play-overlay-btn">
                                        <i class="fas fa-play fs-5 ps-1"></i>
                                    </div>
                                </a>

                                <!-- Cuerpo de la tarjeta -->
                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <span class="text-muted small d-block mb-2">
                                            <i class="far fa-clock me-1"></i> <?php echo $fecha_item; ?>
                                        </span>
                                        <h5 class="card-title fw-bold text-dark lh-base mb-3 fs-6">
                                            <a href="<?php echo $url_item; ?>" target="_blank" class="text-dark text-decoration-none">
                                                <?php echo htmlspecialchars($item['titulo']); ?>
                                            </a>
                                        </h5>
                                    </div>

                                    <!-- Botón de acción -->
                                    <div class="pt-3 border-top mt-3">
                                        <a href="<?php echo $url_item; ?>" target="_blank" class="text-danger fw-bold text-decoration-none d-inline-flex align-items-center gap-2">
                                            <span>Escuchar episodio</span> <i class="fas fa-arrow-right fs-6"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php elseif (!$destacado): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">No hay episodios registrados en este momento.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <!-- FOOTER INCLUIDO -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>