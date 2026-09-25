<?php
include __DIR__ . '/config/conexion.php';
include 'includes/header.php';


// Función para formatear fecha (ej: "Ago 28, 2026")
function formatearFechaBoletin($fecha) {
    if (empty($fecha)) return '';
    $timestamp = strtotime($fecha);
    $meses = [
        1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
        5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
        9 => 'Set', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
    ];
    return $meses[(int)date('n', $timestamp)] . ' ' . date('d', $timestamp) . ', ' . date('Y', $timestamp);
}

// Consulta de boletines ordenados del más reciente al más antiguo
$sql_boletines = "SELECT * FROM boletines ORDER BY fecha_publicacion DESC";
$res_boletines = $conexion->query($sql_boletines);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletines NTEP - Diálogo y Desarrollo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">


    <main class="py-5">
        <div class="container">

            <!-- Título y Breadcrumbs -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="fw-bold text-dark m-0 fs-2">Boletines NTEP</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="index.php" class="text-secondary text-decoration-none">Inicio</a></li>
                        <li class="breadcrumb-item active text-muted" aria-current="page">Boletines</li>
                    </ol>
                </nav>
            </div>

            <!-- Grilla de Boletines -->
            <div class="row g-4">
                <?php if ($res_boletines && $res_boletines->num_rows > 0): ?>
                    <?php while($boletin = $res_boletines->fetch_assoc()): 
                        $fecha_formateada = formatearFechaBoletin($boletin['fecha_publicacion']);
                        $pdf_path = htmlspecialchars($boletin['archivo_pdf']);
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden bg-white rounded-4">
                                
                                <!-- Imagen enlazada al PDF -->
                                <a href="<?php echo $pdf_path; ?>" target="_blank" class="d-block overflow-hidden">
                                    <img src="<?php echo htmlspecialchars($boletin['foto_portada'] ?: 'assets/images/bannerimg.jpg'); ?>" 
                                         alt="Boletín <?php echo htmlspecialchars($boletin['numero_boletin']); ?>" 
                                         class="card-img-top w-100" 
                                         style="height: 380px; object-fit: cover; object-position: top;">
                                </a>

                                <!-- Cuerpo de la tarjeta -->
                                <div class="card-body p-4 d-flex flex-column justify-content-between bg-white">
                                    <div>
                                        <?php if(!empty($boletin['numero_boletin'])): ?>
                                            <span class="badge bg-danger-subtle text-danger mb-2 px-3 py-2 rounded-pill fw-semibold">
                                                Boletín N° <?php echo htmlspecialchars($boletin['numero_boletin']); ?>
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <!-- Pie con Fecha arriba y Enlace abajo -->
                                    <div class="pt-3 border-top mt-2">
                                        <small class="text-muted fw-medium d-block mb-2">
                                            <?php echo $fecha_formateada; ?>
                                        </small>
                                        
                                        <a href="<?php echo $pdf_path; ?>" target="_blank" class="text-danger fw-bold text-decoration-none d-inline-flex align-items-center gap-1">
                                            Ver boletín <i class="fas fa-arrow-right fs-6"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">No hay boletines disponibles en este momento.</p>
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