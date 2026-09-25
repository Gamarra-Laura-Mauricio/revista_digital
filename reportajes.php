<?php
include __DIR__ . '/config/conexion.php';
include 'includes/header.php';

// Función para transformar YYYY-MM-DD en formato "nov 20, 2026"
function formatearFechaTexto($fecha) {
    if (empty($fecha)) return '';
    $timestamp = strtotime($fecha);
    $meses = [
        1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr',
        5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago',
        9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic'
    ];
    return $meses[(int)date('n', $timestamp)] . ' ' . date('d', $timestamp) . ', ' . date('Y', $timestamp);
}

// Consulta ordenada por fecha de publicación descendente
$sql_reportajes = "SELECT * FROM reportajes ORDER BY fecha_publicacion DESC";
$res_reportajes = $conexion->query($sql_reportajes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportajes - Diálogo y Desarrollo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>
<body>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="py-5 bg-white">
        <div class="container py-lg-4">
            
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fw-bold display-5 text-dark m-0">Reportajes</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-secondary text-decoration-none">Inicio</a></li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">Reportajes</li>
                    </ol>
                </nav>
            </div>

            <div class="row g-4">
                <?php if ($res_reportajes && $res_reportajes->num_rows > 0): ?>
                    <?php while($item = $res_reportajes->fetch_assoc()): 
                        $fecha_formateada = formatearFechaTexto($item['fecha_publicacion']);
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="background-color: #f6f7f9; border-radius: 18px;">
                                
                                <!-- Enlace corregido a detalle_reportaje.php -->
                                <a href="detalle_reportaje.php?id=<?php echo $item['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($item['foto_principal'] ?: 'assets/images/bannerimg.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($item['titulo']); ?>" 
                                         class="card-img-top" 
                                         style="height: 230px; object-fit: cover;">
                                </a>

                                <div class="card-body p-4 d-flex flex-column justify-content-between">
                                    <div>
                                        <p class="text-muted small mb-2 text-lowercase" style="font-size: 0.9rem;">
                                            <?php echo htmlspecialchars($fecha_formateada); ?>
                                        </p>

                                        <h5 class="card-title fw-bold lh-base mb-4" style="font-size: 1.1rem;">
                                            <!-- Enlace corregido a detalle_reportaje.php -->
                                            <a href="detalle_reportaje.php?id=<?php echo $item['id']; ?>" class="text-dark text-decoration-none">
                                                <?php echo htmlspecialchars($item['titulo']); ?>
                                            </a>
                                        </h5>
                                    </div>

                                    <div>
                                        <!-- Enlace corregido a detalle_reportaje.php -->
                                        <a href="detalle_reportaje.php?id=<?php echo $item['id']; ?>" class="text-danger fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
                                            Leer <i class="fas fa-arrow-right fs-6"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">No hay reportajes registrados por el momento.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- INCLUIR FOOTER -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</body>
</html>