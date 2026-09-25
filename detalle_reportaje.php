<?php
include __DIR__ . '/config/conexion.php';
include 'includes/header.php';
// 1. Validar ID del reportaje
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header("Location: reportajes.php");
    exit();
}

$id_reportaje = (int)$_GET['id'];

// 2. Consulta del reportaje principal usando campos exactos de tu BD
$stmt = $conexion->prepare("SELECT * FROM reportajes WHERE id = ?");
$stmt->bind_param("i", $id_reportaje);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: reportajes.php");
    exit();
}

$reportaje = $resultado->fetch_assoc();

// 3. Consulta lateral: Últimas noticias (excluyendo la actual)
$stmt_ultimas = $conexion->prepare("SELECT id, titulo, fecha_publicacion FROM reportajes WHERE id != ? ORDER BY fecha_publicacion DESC LIMIT 3");
$stmt_ultimas->bind_param("i", $id_reportaje);
$stmt_ultimas->execute();
$res_ultimas = $stmt_ultimas->get_result();

// 4. Consulta lateral: Archivos dinámicos agrupados por Mes y Año
$sql_archivos = "SELECT DISTINCT YEAR(fecha_publicacion) AS anio, MONTH(fecha_publicacion) AS mes 
                 FROM reportajes 
                 ORDER BY fecha_publicacion DESC";
$res_archivos = $conexion->query($sql_archivos);

// Funciones de formato de fecha
function formatearFechaTexto($fecha) {
    if (empty($fecha)) return '';
    $timestamp = strtotime($fecha);
    $meses = [
        1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
        5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
        9 => 'Set', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
    ];
    return $meses[(int)date('n', $timestamp)] . ' ' . date('d', $timestamp) . ', ' . date('Y', $timestamp);
}

function obtenerNombreMes($num_mes) {
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
    return $meses[(int)$num_mes] ?? '';
}

$fecha_formateada = formatearFechaTexto($reportaje['fecha_publicacion']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($reportaje['titulo']); ?> - Diálogo y Desarrollo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style-starter.css">
</head>
<body>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="py-5 bg-white">
        <div class="container">
            <div class="row g-5">

                <!-- COLUMNA IZQUIERDA: REPORTAJE -->
                <div class="col-lg-8">
                    <article>
                        <!-- 1. Título principal -->
                        <h1 class="fw-bold display-6 text-dark mb-4 lh-base">
                            <?php echo htmlspecialchars($reportaje['titulo']); ?>
                        </h1>

                        <!-- 2. Imagen Principal (foto_principal) -->
                        <div class="mb-4">
                            <img src="<?php echo htmlspecialchars($reportaje['foto_principal'] ?: 'assets/images/bannerimg.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($reportaje['titulo']); ?>" 
                                 class="img-fluid w-100 rounded-3 shadow-sm" 
                                 style="max-height: 500px; object-fit: cover;">
                        </div>

                        <!-- 3. Resumen corto entre comillas (resumen_corto) -->
                        <?php if (!empty($reportaje['resumen_corto'])): ?>
                            <div class="my-4 px-3 py-2 text-secondary fst-italic fs-5" style="line-height: 1.6;">
                                “<?php echo htmlspecialchars($reportaje['resumen_corto']); ?>”
                            </div>
                        <?php endif; ?>

                        <!-- 4. Desarrollo / Cuerpo de la noticia (desarrollo) -->
                        <div class="lh-lg text-dark fs-6 my-4">
                            <?php echo nl2br($reportaje['desarrollo']); ?>
                        </div>

                        <!-- 5. Adjunto PDF si existe en BD (pdf_adjunto) -->
                        <?php if (!empty($reportaje['pdf_adjunto'])): ?>
                            <div class="my-4 p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="fas fa-file-pdf text-danger fs-2"></i>
                                    <div>
                                        <h6 class="m-0 fw-bold">Documento Adjunto</h6>
                                        <small class="text-muted">Descarga el reporte completo en formato PDF</small>
                                    </div>
                                </div>
                                <a href="<?php echo htmlspecialchars($reportaje['pdf_adjunto']); ?>" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    Ver PDF
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- 6. Botón de regreso a Reportajes -->
                        <div class="mt-5 pt-3 border-top">
                            <a href="reportajes.php" class="text-danger fw-bold text-decoration-none d-inline-flex align-items-center gap-2 fs-5">
                                <i class="fas fa-arrow-left"></i> Reportajes
                            </a>
                        </div>
                    </article>
                </div>


                <!-- COLUMNA DERECHA: BARRA LATERAL -->
                <div class="col-lg-4">
                    <aside class="ps-lg-3">
                        
                        <!-- Bloque 1: Últimas noticias -->
                        <div class="mb-5">
                            <h5 class="fw-bold text-dark mb-4">Últimas noticias</h5>
                            
                            <?php if ($res_ultimas && $res_ultimas->num_rows > 0): ?>
                                <?php while($item_lateral = $res_ultimas->fetch_assoc()): ?>
                                    <div class="mb-4">
                                        <h6 class="fw-semibold lh-base mb-1">
                                            <a href="detalle_reportaje.php?id=<?php echo $item_lateral['id']; ?>" class="text-dark text-decoration-none">
                                                <?php echo htmlspecialchars($item_lateral['titulo']); ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted" style="font-size: 0.85rem;">
                                            <?php echo formatearFechaTexto($item_lateral['fecha_publicacion']); ?>
                                        </small>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Bloque 2: Archivos (Traídos directamente de las fechas registradas en la BD) -->
                        <div>
                            <h5 class="fw-bold text-dark mb-3">Archivos</h5>
                            <ul class="list-unstyled text-muted lh-lg">
                                <?php if ($res_archivos && $res_archivos->num_rows > 0): ?>
                                    <?php while($arc = $res_archivos->fetch_assoc()): ?>
                                        <li>• <?php echo obtenerNombreMes($arc['mes']) . ' ' . $arc['anio']; ?></li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li>• Sin archivos previas</li>
                                <?php endif; ?>
                            </ul>
                        </div>

                    </aside>
                </div>

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