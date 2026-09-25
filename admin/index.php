<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_login();

$pageTitle = 'Dashboard';
$active = 'dashboard';
$crumbs = 'Administración|Dashboard';

// Obtener el nombre del usuario mapeado a las columnas 'nombres' y 'ap_paterno' de la BD
$user = current_user();
$nombreUsuario = trim(($user['nombres'] ?? '') . ' ' . ($user['ap_paterno'] ?? ''));
if (empty($nombreUsuario)) {
    $nombreUsuario = $user['nombre_completo'] ?? 'Usuario';
}

// Obtener conteos de las tablas de la BD revista_digital
$counts = [];
$tables = ['reportajes', 'noticias', 'boletines', 'podcasts', 'videos', 'autores', 'usuarios'];

foreach ($tables as $table) {
    $result = db()->query("SELECT COUNT(*) AS total FROM `$table`");
    $counts[$table] = (int)($result->fetch_assoc()['total'] ?? 0);
}

// Obtener publicaciones recientes ordenadas por fecha_publicacion
$recent = db()->query("
    SELECT id, titulo, fecha_publicacion, 'Reportaje' AS tipo FROM reportajes 
    UNION ALL 
    SELECT id, titulo, fecha_publicacion, 'Noticia' AS tipo FROM noticias 
    UNION ALL 
    SELECT id, titulo, fecha_publicacion, 'Podcast' AS tipo FROM podcasts 
    UNION ALL 
    SELECT id, titulo, fecha_publicacion, 'Video' AS tipo FROM videos 
    ORDER BY fecha_publicacion DESC 
    LIMIT 8
");

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-text">
        <span class="eyebrow">Panel administrativo</span>
        <h1 class="hero-title">Bienvenido, <span class="accent"><?= e($nombreUsuario) ?></span></h1>
        <p class="hero-sub">Desde aquí puedes administrar los contenidos publicados en tu sistema de noticias.</p>
    </div>
    <div class="hero-actions">
        <a class="btn btn--primary" href="gestion.php?mod=reportajes">+ Nuevo reportaje</a>
    </div>
</section>

<section class="kpi-grid" aria-label="Resumen">
    <?php 
    $cards = [
        ['reportajes', 'Reportajes', 'c-danger'],
        ['noticias',   'Noticias',   'c-success'],
        ['boletines',  'Boletines',  'c-purple'],
        ['podcasts',   'Podcasts',   'c-primary']
    ]; 
    foreach ($cards as [$key, $label, $color]): 
    ?>
        <article class="kpi-card <?= $color ?>">
            <div class="kpi-top">
                <div class="kpi-label"><?= $label ?></div>
            </div>
            <div class="kpi-value"><?= $counts[$key] ?></div>
            <div class="kpi-compare">
                <a class="stat-link" href="gestion.php?mod=<?= $key ?>">Administrar contenido &rarr;</a>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<div class="grid">
    <section class="col-8 card">
        <div class="card-head">
            <div class="card-title-wrap">
                <span class="eyebrow">Contenido</span>
                <h2 class="card-title">Publicaciones recientes</h2>
            </div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $recent->fetch_assoc()): ?>
                        <tr>
                            <td><span class="badge solid"><?= e($row['tipo']) ?></span></td>
                            <td><strong><?= e($row['titulo']) ?></strong></td>
                            <td><?= e($row['fecha_publicacion']) ?></td>
                            <td>
                                <a class="btn btn--ghost btn--small" href="gestion.php?mod=<?= $row['tipo'] === 'Reportaje' ? 'reportajes' : strtolower($row['tipo']) ?>&action=edit&id=<?= $row['id'] ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="col-4 card">
        <div class="card-head">
            <div class="card-title-wrap">
                <span class="eyebrow">Sistema</span>
                <h2 class="card-title">Resumen</h2>
            </div>
        </div>
        <div class="todo-list">
            <div class="todo-item">
                <span class="todo-text">Videos</span>
                <span class="todo-badge upcoming"><?= $counts['videos'] ?></span>
            </div>
            <div class="todo-item">
                <span class="todo-text">Autores</span>
                <span class="todo-badge low"><?= $counts['autores'] ?></span>
            </div>
            <div class="todo-item">
                <span class="todo-text">Usuarios</span>
                <span class="todo-badge urgent"><?= $counts['usuarios'] ?></span>
            </div>
        </div>
        <div class="form-actions" style="margin-top:18px">
            <a class="btn btn--primary" href="gestion.php?mod=noticias">Gestionar noticias</a>
        </div>
    </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>