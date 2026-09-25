<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_login();

// Mapeo exacto de módulos con las columnas de la BD 'revista_digital'
$modules = [
    'reportajes' => [
        'table'  => 'reportajes',
        'label'  => 'Reportajes',
        'fields' => ['titulo', 'resumen_corto', 'desarrollo', 'foto_principal', 'pdf_adjunto', 'fecha_publicacion', 'es_destacado', 'autor_id'],
        'image'  => 'foto_principal'
    ],
    'noticias' => [
        'table'  => 'noticias',
        'label'  => 'Noticias',
        'fields' => ['titulo', 'foto', 'link_externo', 'fecha_publicacion'],
        'image'  => 'foto'
    ],
    'boletines' => [
        'table'  => 'boletines',
        'label'  => 'Boletines',
        'fields' => ['numero_boletin', 'resumen', 'foto_portada', 'archivo_pdf', 'fecha_publicacion'],
        'image'  => 'foto_portada'
    ],
    'podcasts' => [
        'table'  => 'podcasts',
        'label'  => 'Podcasts',
        'fields' => ['titulo', 'url_embed', 'fecha_publicacion']
    ],
    'videos' => [
        'table'  => 'videos',
        'label'  => 'Videos',
        'fields' => ['titulo', 'url_embed', 'fecha_publicacion']
    ],
    'autores' => [
        'table'  => 'autores',
        'label'  => 'Autores',
        'fields' => ['nombres', 'ap_paterno', 'ap_materno', 'nickname', 'es_nickname']
    ],
    'usuarios' => [
        'table'  => 'usuarios',
        'label'  => 'Usuarios',
        'fields' => ['nombres', 'ap_paterno', 'ap_materno', 'email', 'password_hash', 'rol']
    ],
];

$mod = $_GET['mod'] ?? 'reportajes';
if (!isset($modules[$mod])) {
    $mod = 'reportajes';
}

if ($mod === 'usuarios') {
    require_admin();
}

$config = $modules[$mod];
$table = $config['table'];
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

function module_url(string $mod, string $extra = ''): string { 
    return 'gestion.php?mod=' . rawurlencode($mod) . ($extra ? '&' . $extra : ''); 
}

function get_default_author_id(): int {
    $defaultName = 'Dialogo y Desarrollo peru';
    $stmt = db()->prepare("SELECT id FROM autores WHERE nickname = ? OR nombres = ? LIMIT 1");
    $stmt->bind_param('ss', $defaultName, $defaultName);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        return (int)$res['id'];
    }

    $stmtInsert = db()->prepare("INSERT INTO autores (nombres, nickname, es_nickname) VALUES (?, ?, 1)");
    $stmtInsert->bind_param('ss', $defaultName, $defaultName);
    $stmtInsert->execute();

    return (int)db()->insert_id;
}

function get_or_create_author_id(?string $authorInput): int {
    $authorInput = trim((string)$authorInput);
    if ($authorInput === '') {
        return get_default_author_id();
    }

    $stmt = db()->prepare("
        SELECT id FROM autores 
        WHERE nickname = ? 
           OR nombres = ? 
           OR CONCAT_WS(' ', nombres, ap_paterno, ap_materno) = ? 
        LIMIT 1
    ");
    $stmt->bind_param('sss', $authorInput, $authorInput, $authorInput);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        return (int)$res['id'];
    }

    $stmtInsert = db()->prepare("INSERT INTO autores (nombres, nickname, es_nickname) VALUES (?, ?, 1)");
    $stmtInsert->bind_param('ss', $authorInput, $authorInput);
    $stmtInsert->execute();

    return (int)db()->insert_id;
}

$authors = db()->query("
    SELECT id, 
           IF(es_nickname = 1 AND nickname IS NOT NULL AND nickname != '', nickname, CONCAT_WS(' ', nombres, ap_paterno, ap_materno)) AS nombre 
    FROM autores 
    ORDER BY nombres, ap_paterno
")->fetch_all(MYSQLI_ASSOC);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete_id'])) {
            $deleteId = (int)$_POST['delete_id']; 
            $stmt = db()->prepare("DELETE FROM `$table` WHERE id = ?"); 
            $stmt->bind_param('i', $deleteId); 
            $stmt->execute(); 
            flash('success', 'Registro eliminado correctamente.'); 
            redirect(module_url($mod));
        }

        $data = [];
        foreach ($config['fields'] as $field) {
            if ($field === 'password_hash') continue;
            $data[$field] = $_POST[$field] ?? null;
        }

        if (in_array('autor_id', $config['fields'], true)) {
            $authorInput = $_POST['autor_nombre'] ?? '';
            $data['autor_id'] = get_or_create_author_id($authorInput);
        }

        if (in_array('es_destacado', $config['fields'], true)) {
            $data['es_destacado'] = isset($_POST['es_destacado']) ? 1 : 0;
        }
        if (in_array('es_nickname', $config['fields'], true)) {
            $data['es_nickname'] = isset($_POST['es_nickname']) ? 1 : 0;
        }

        if (in_array($mod, ['reportajes', 'noticias', 'boletines', 'podcasts', 'videos'], true)) {
            $data['usuario_id'] = (int)(current_user()['id'] ?? 0);
        }

        if ($mod === 'usuarios') {
            $password = (string)($_POST['password'] ?? '');
            if ($id === 0 && $password === '') {
                throw new RuntimeException('La contraseña es obligatoria para crear un usuario.');
            }
            if ($password !== '') {
                $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $data['rol'] = $_POST['rol'] ?? 'redactor';

            $nombresVal = trim((string)($data['nombres'] ?? ''));
            $paternoVal = trim((string)($data['ap_paterno'] ?? ''));
            $maternoVal = trim((string)($data['ap_materno'] ?? ''));

            if ($nombresVal !== '' && $paternoVal !== '') {
                $primerNombre = explode(' ', $nombresVal)[0];
                $letraP = mb_substr($paternoVal, 0, 1, 'UTF-8');
                $letraM = mb_substr($maternoVal, 0, 1, 'UTF-8');

                $baseRaw = mb_strtolower($primerNombre . $letraP . $letraM, 'UTF-8');
                $mapaAcentos = [
                    'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
                    'ä'=>'a', 'ë'=>'e', 'ï'=>'i', 'ö'=>'o', 'ü'=>'u',
                    'à'=>'a', 'è'=>'e', 'ì'=>'i', 'ò'=>'o', 'ù'=>'u',
                    'ñ'=>'n'
                ];
                $baseLimpia = strtr($baseRaw, $mapaAcentos);
                $baseLimpia = preg_replace('/[^a-z0-9]/', '', $baseLimpia);

                $data['email'] = $baseLimpia . '@gmail.com';
            }
        }

        if (isset($_FILES['foto_archivo']) && $_FILES['foto_archivo']['error'] !== UPLOAD_ERR_NO_FILE && !empty($config['image'])) {
            $data[$config['image']] = upload_file('foto_archivo', $mod, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        }
        if ($mod === 'boletines' && isset($_FILES['pdf_archivo']) && $_FILES['pdf_archivo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $data['archivo_pdf'] = upload_file('pdf_archivo', 'boletines', ['pdf']);
        }
        if ($mod === 'reportajes' && isset($_FILES['pdf_archivo']) && $_FILES['pdf_archivo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $data['pdf_adjunto'] = upload_file('pdf_archivo', 'reportajes', ['pdf']);
        }

        if ($id > 0) {
            $set = []; $types = ''; $values = [];
            foreach ($data as $k => $v) { 
                if ($k === 'password_hash' && empty($v)) continue; 
                $set[] = "`$k`=?"; 
                $types .= is_int($v) ? 'i' : 's'; 
                $values[] = $v; 
            }
            if (!$set) throw new RuntimeException('No hay cambios para guardar.');
            $types .= 'i'; $values[] = $id; 
            $stmt = db()->prepare("UPDATE `$table` SET " . implode(',', $set) . " WHERE id = ?"); 
            $stmt->bind_param($types, ...$values); 
            $stmt->execute();
            flash('success', 'Registro actualizado correctamente.');
        } else {
            $cols = []; $marks = []; $types = ''; $values = []; 
            foreach ($data as $k => $v) { 
                if ($k === 'password_hash' && empty($v)) continue; 
                $cols[] = "`$k`"; 
                $marks[] = '?'; 
                $types .= is_int($v) ? 'i' : 's'; 
                $values[] = $v; 
            }
            $stmt = db()->prepare("INSERT INTO `$table` (" . implode(',', $cols) . ") VALUES (" . implode(',', $marks) . ")"); 
            $stmt->bind_param($types, ...$values); 
            $stmt->execute(); 
            flash('success', 'Registro creado correctamente.');
        }
        redirect(module_url($mod));
    }
} catch (Throwable $ex) { 
    flash('danger', $ex->getMessage()); 
    redirect(module_url($mod, $id ? 'action=edit&id=' . $id : 'action=list')); 
}

$edit = null;
if ($action === 'edit' && $id > 0) {
    $stmt = db()->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $edit = $stmt->get_result()->fetch_assoc(); 
    if (!$edit) {
        flash('danger', 'Registro no encontrado.');
        redirect(module_url($mod));
    }
}

$pageTitle = $config['label']; 
$active = $mod; 
$crumbs = 'Administración|' . $config['label'];
include __DIR__ . '/includes/header.php';
?>

<!-- Hojas de estilo de Quill Editor -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
    /* Ajustes de adaptabilidad responsiva */
    .table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        min-width: 650px;
    }

    .ql-toolbar.ql-snow {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        border-radius: 6px 6px 0 0;
    }

    .ql-container.ql-snow {
        border-radius: 0 0 6px 6px;
    }

    .filter-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }

    .filter-item {
        flex: 1 1 180px;
    }

    .filter-item-sm {
        flex: 1 1 90px;
    }

    .filter-item-md {
        flex: 1 1 140px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        flex: 1 1 100%;
        justify-content: flex-end;
    }

    @media (min-width: 768px) {
        .filter-actions {
            flex: 0 0 auto;
        }
    }

    @media (max-width: 991px) {
        .form-grid-3 {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 16px;
        }
        .form-grid-3 .full {
            grid-column: span 2;
        }
    }

    @media (max-width: 576px) {
        .hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .hero-actions {
            width: 100%;
        }
        .hero-actions .btn {
            width: 100%;
            text-align: center;
        }
        .form-grid-3 {
            grid-template-columns: 1fr !important;
        }
        .form-grid-3 .full {
            grid-column: span 1;
        }
        .filter-item, .filter-item-sm, .filter-item-md {
            flex: 1 1 100%;
        }
        .filter-actions {
            width: 100%;
        }
        .filter-actions .btn {
            flex: 1;
            text-align: center;
            justify-content: center;
        }
    }
</style>

<section class="hero">
    <div class="hero-text">
        <span class="eyebrow">Gestión de contenido</span>
        <h1 class="hero-title"><?= $edit ? 'Editar' : 'Administrar' ?> <?= e($config['label']) ?></h1>
        <p class="hero-sub">Gestiona los registros de <strong><?= e($config['label']) ?></strong> directamente desde el panel.</p>
    </div>
    <div class="hero-actions">
        <?php if ($action === 'list'): ?>
            <a class="btn btn--primary" href="<?= module_url($mod, 'action=new') ?>">+ Nuevo registro</a>
        <?php else: ?>
            <a class="btn btn--ghost" href="<?= module_url($mod) ?>">← Volver</a>
        <?php endif; ?>
    </div>
</section>

<?php if ($action === 'new' || $action === 'edit'): ?>
<section class="card">
    <form method="post" enctype="multipart/form-data" id="form-gestion">
        <div class="form-grid-3">
            <?php foreach ($config['fields'] as $field): $value = $edit[$field] ?? ''; ?>
                <?php if ($field === 'desarrollo'): ?>
                    <div class="field full">
                        <label class="field-label">Desarrollo (Editor Enriquecido)</label>
                        <div id="quill-editor" style="min-height: 280px; background: #fff; color: #333; font-size: 15px;">
                            <?= $value ?>
                        </div>
                        <input type="hidden" name="desarrollo" id="desarrollo-input" value="<?= e((string)$value) ?>">
                    </div>
                <?php elseif (in_array($field, ['resumen_corto', 'resumen', 'link_externo', 'url_embed'], true)): ?>
                    <div class="field">
                        <label class="field-label"><?= e(ucwords(str_replace('_', ' ', $field))) ?></label>
                        <input class="input" name="<?= e($field) ?>" value="<?= e((string)$value) ?>">
                    </div>
                <?php elseif ($field === 'fecha_publicacion'): ?>
                    <div class="field">
                        <label class="field-label">Fecha de publicación</label>
                        <input class="input" type="date" name="fecha_publicacion" value="<?= $value ? e(date('Y-m-d', strtotime((string)$value))) : e(date('Y-m-d')) ?>" required>
                    </div>
                <?php elseif (in_array($field, ['es_destacado', 'es_nickname'], true)): ?>
                    <div class="field">
                        <label class="check">
                            <input type="checkbox" name="<?= e($field) ?>" value="1" <?= !empty($value) ? 'checked' : '' ?>>
                            <span class="box"></span> <?= e(ucwords(str_replace('_', ' ', $field))) ?>
                        </label>
                    </div>
                <?php elseif ($field === 'autor_id'): ?>
                    <?php 
                        $currentAuthorName = '';
                        if (!empty($value)) {
                            foreach ($authors as $a) {
                                if ((int)$a['id'] === (int)$value) {
                                    $currentAuthorName = $a['nombre'];
                                    break;
                                }
                            }
                        }
                    ?>
                    <div class="field">
                        <label class="field-label">Autor (Selecciona o escribe uno nuevo)</label>
                        <input class="input" list="authors_list" name="autor_nombre" value="<?= e($currentAuthorName) ?>" placeholder="Dialogo y Desarrollo peru">
                        <datalist id="authors_list">
                            <?php foreach ($authors as $a): ?>
                                <option value="<?= e($a['nombre']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                <?php elseif (in_array($field, ['foto_principal', 'foto', 'foto_portada'], true)): ?>
                    <div class="field">
                        <label class="field-label"><?= e(ucwords(str_replace('_', ' ', $field))) ?><?= !empty($value) ? ' actual' : '' ?></label>
                        <?php if ($value): ?>
                            <div style="margin-bottom:8px">
                                <img class="thumb" src="<?= e((string)$value) ?>" alt="Imagen actual" style="max-width:120px; max-height:120px; object-fit:cover; border-radius:6px;">
                            </div>
                        <?php endif; ?>
                        <input class="input" type="file" name="foto_archivo" accept="image/*">
                        <div class="file-note">JPG, PNG, WEBP o GIF. Máximo 10 MB.</div>
                    </div>
                <?php elseif (in_array($field, ['pdf_adjunto', 'archivo_pdf'], true)): ?>
                    <div class="field">
                        <label class="field-label"><?= e(ucwords(str_replace('_', ' ', $field))) ?></label>
                        <input class="input" type="file" name="pdf_archivo" accept="application/pdf">
                        <div class="file-note">PDF. Máximo 10 MB.</div>
                        <?php if ($value): ?>
                            <div class="file-note">Archivo actual: <?= e((string)$value) ?></div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($field === 'email' && $mod === 'usuarios'): ?>
                    <div class="field">
                        <label class="field-label">Correo electrónico (Generado automáticamente)</label>
                        <input class="input" name="email" value="<?= e((string)$value) ?>" placeholder="Se autogenerará con el nombre y apellidos..." readonly style="background-color: rgba(128,128,128,0.15); cursor: not-allowed;">
                    </div>
                <?php elseif ($field === 'password_hash'): ?>
                    <div class="field">
                        <label class="field-label">Contraseña <?= $edit ? '(dejar vacía para no cambiarla)' : '' ?></label>
                        <input class="input" type="password" name="password" <?= !$edit ? 'required' : '' ?>>
                    </div>
                <?php elseif ($field === 'rol'): ?>
                    <div class="field">
                        <label class="field-label">Rol del Usuario</label>
                        <select class="select" name="rol" required>
                            <option value="redactor" <?= ($value === 'redactor' || empty($value)) ? 'selected' : '' ?>>Redactor (No Admin)</option>
                            <option value="editor" <?= ($value === 'editor') ? 'selected' : '' ?>>Editor (No Admin)</option>
                            <option value="admin" <?= ($value === 'admin') ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>
                <?php else: ?>
                    <div class="field">
                        <label class="field-label"><?= e(ucwords(str_replace('_', ' ', $field))) ?></label>
                        <input class="input" name="<?= e($field) ?>" value="<?= e((string)$value) ?>" <?= in_array($field, ['ap_materno', 'nickname'], true) ? '' : 'required' ?>>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="form-actions" style="margin-top: 20px;">
            <span></span>
            <button class="btn btn--primary" type="submit">Guardar registro</button>
        </div>
    </form>
</section>

<!-- Librería JS de Quill Editor -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quillContainer = document.getElementById('quill-editor');
    if (quillContainer) {
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, false] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'clean']
                ]
            }
        });

        const form = document.getElementById('form-gestion');
        const hiddenInput = document.getElementById('desarrollo-input');

        form.addEventListener('submit', function() {
            hiddenInput.value = quill.root.innerHTML;
        });
    }

    const inputNombres = document.querySelector('input[name="nombres"]');
    const inputPaterno = document.querySelector('input[name="ap_paterno"]');
    const inputMaterno = document.querySelector('input[name="ap_materno"]');
    const inputEmail = document.querySelector('input[name="email"]');

    if (inputNombres && inputPaterno && inputEmail) {
        function generarCorreoLive() {
            let nombres = inputNombres.value.trim().toLowerCase();
            let paterno = inputPaterno.value.trim().toLowerCase();
            let materno = inputMaterno ? inputMaterno.value.trim().toLowerCase() : '';

            if (nombres && paterno) {
                let primerNombre = nombres.split(' ')[0];
                let letraP = paterno.charAt(0);
                let letraM = materno.charAt(0);

                let base = primerNombre + letraP + letraM;
                base = base.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                base = base.replace(/ñ/g, "n").replace(/[^a-z0-9]/g, "");

                inputEmail.value = base + '@gmail.com';
            }
        }

        inputNombres.addEventListener('input', generarCorreoLive);
        inputPaterno.addEventListener('input', generarCorreoLive);
        if (inputMaterno) inputMaterno.addEventListener('input', generarCorreoLive);
    }
});
</script>

<?php else: ?>

<?php
$filterQ     = trim($_GET['q'] ?? '');
$filterId    = (int)($_GET['filter_id'] ?? 0);
$filterAutor = (int)($_GET['filter_autor'] ?? 0);
$filterRol   = trim($_GET['filter_rol'] ?? '');
$filterFrom  = $_GET['date_from'] ?? '';
$filterTo    = $_GET['date_to'] ?? '';

$where = [];
$params = [];
$types = '';

if ($filterQ !== '') {
    $searchFields = [];
    if (in_array('titulo', $config['fields'], true)) $searchFields[] = "`titulo` LIKE ?";
    if (in_array('resumen_corto', $config['fields'], true)) $searchFields[] = "`resumen_corto` LIKE ?";
    if (in_array('resumen', $config['fields'], true)) $searchFields[] = "`resumen` LIKE ?";
    if (in_array('nombres', $config['fields'], true)) $searchFields[] = "`nombres` LIKE ?";
    if (in_array('email', $config['fields'], true)) $searchFields[] = "`email` LIKE ?";

    if (!empty($searchFields)) {
        $where[] = '(' . implode(' OR ', $searchFields) . ')';
        $likeVal = '%' . $filterQ . '%';
        foreach ($searchFields as $_) {
            $params[] = $likeVal;
            $types .= 's';
        }
    }
}

if ($filterId > 0) {
    $where[] = "`id` = ?";
    $params[] = $filterId;
    $types .= 'i';
}

if ($filterAutor > 0 && in_array('autor_id', $config['fields'], true)) {
    $where[] = "`autor_id` = ?";
    $params[] = $filterAutor;
    $types .= 'i';
}

if ($filterRol !== '' && in_array('rol', $config['fields'], true)) {
    $where[] = "`rol` = ?";
    $params[] = $filterRol;
    $types .= 's';
}

if (!empty($filterFrom) && in_array('fecha_publicacion', $config['fields'], true)) {
    $where[] = "DATE(`fecha_publicacion`) >= ?";
    $params[] = $filterFrom;
    $types .= 's';
}

if (!empty($filterTo) && in_array('fecha_publicacion', $config['fields'], true)) {
    $where[] = "DATE(`fecha_publicacion`) <= ?";
    $params[] = $filterTo;
    $types .= 's';
}

$sql = "SELECT * FROM `$table`" . (!empty($where) ? " WHERE " . implode(" AND ", $where) : "") . " ORDER BY id DESC";

if (!empty($params)) {
    $stmtList = db()->prepare($sql);
    $stmtList->bind_param($types, ...$params);
    $stmtList->execute();
    $result = $stmtList->get_result();
} else {
    $result = db()->query($sql);
}
?>

<section class="card" style="margin-bottom: 20px; padding: 18px;">
    <form method="get" action="gestion.php" class="filter-container">
        <input type="hidden" name="mod" value="<?= e($mod) ?>">
        
        <div class="filter-item">
            <label class="field-label" style="font-size: 12px; margin-bottom: 4px; display: block;">Buscar texto</label>
            <input class="input" type="text" name="q" placeholder="Buscar por nombre, correo, título..." value="<?= e($filterQ) ?>" style="height: 38px;">
        </div>

        <div class="filter-item-sm">
            <label class="field-label" style="font-size: 12px; margin-bottom: 4px; display: block;">ID</label>
            <input class="input" type="number" name="filter_id" placeholder="ID" value="<?= $filterId > 0 ? $filterId : '' ?>" style="height: 38px;">
        </div>

        <?php if ($mod === 'usuarios'): ?>
        <div class="filter-item-md">
            <label class="field-label" style="font-size: 12px; margin-bottom: 4px; display: block;">Filtrar por Rol</label>
            <select class="select" name="filter_rol" style="height: 38px;">
                <option value="">Todos los roles</option>
                <option value="redactor" <?= $filterRol === 'redactor' ? 'selected' : '' ?>>Redactor (No Admin)</option>
                <option value="editor" <?= $filterRol === 'editor' ? 'selected' : '' ?>>Editor (No Admin)</option>
                <option value="admin" <?= $filterRol === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <?php endif; ?>

        <?php if (in_array('autor_id', $config['fields'], true)): ?>
        <div class="filter-item-md">
            <label class="field-label" style="font-size: 12px; margin-bottom: 4px; display: block;">Autor</label>
            <select class="select" name="filter_autor" style="height: 38px;">
                <option value="">Todos los autores</option>
                <?php foreach ($authors as $a): ?>
                    <option value="<?= $a['id'] ?>" <?= $filterAutor === (int)$a['id'] ? 'selected' : '' ?>><?= e($a['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>

        <?php if (in_array('fecha_publicacion', $config['fields'], true)): ?>
        <div class="filter-item-md">
            <label class="field-label" style="font-size: 12px; margin-bottom: 4px; display: block;">Desde</label>
            <input class="input" type="date" name="date_from" value="<?= e($filterFrom) ?>" style="height: 38px;">
        </div>
        <div class="filter-item-md">
            <label class="field-label" style="font-size: 12px; margin-bottom: 4px; display: block;">Hasta</label>
            <input class="input" type="date" name="date_to" value="<?= e($filterTo) ?>" style="height: 38px;">
        </div>
        <?php endif; ?>

        <div class="filter-actions">
            <button class="btn btn--primary" type="submit" style="height: 38px; padding: 0 16px;">Filtrar</button>
            <a class="btn btn--ghost" href="<?= module_url($mod) ?>" style="height: 38px; padding: 0 12px; display: inline-flex; align-items: center;">Limpiar</a>
        </div>
    </form>
</section>

<section class="card">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <?php foreach ($config['fields'] as $field): if ($field === 'password_hash') continue; ?>
                        <th><?= e(ucwords(str_replace('_', ' ', $field))) ?></th>
                    <?php endforeach; ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= e((string)$row['id']) ?></td>
                        <?php foreach ($config['fields'] as $field): ?>
                            <?php if ($field === 'password_hash') continue; ?>
                            <td>
                                <?php if (in_array($field, ['foto_principal', 'foto', 'foto_portada'], true) && !empty($row[$field])): ?>
                                    <img class="thumb" src="<?= e((string)$row[$field]) ?>" alt="Imagen" style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                <?php elseif ($field === 'rol'): ?>
                                    <span style="font-weight:600; padding:3px 8px; border-radius:4px; font-size:12px; background:<?= $row['rol'] === 'admin' ? '#ef4444' : ($row['rol'] === 'editor' ? '#f59e0b' : '#3b82f6') ?>; color:#fff;">
                                        <?= e(strtoupper((string)$row['rol'])) ?>
                                    </span>
                                <?php elseif (in_array($field, ['es_destacado', 'es_nickname'], true)): ?>
                                    <?= !empty($row[$field]) ? 'Sí' : 'No' ?>
                                <?php elseif ($field === 'desarrollo'): ?>
                                    <?= e(mb_strimwidth(strip_tags((string)$row[$field]), 0, 80, '…')) ?>
                                <?php else: ?>
                                    <?= e((string)($row[$field] ?? '')) ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <td>
                            <div class="actions">
                                <a class="btn btn--ghost btn--small" href="<?= module_url($mod, 'action=edit&id=' . $row['id']) ?>">Editar</a>
                                <form method="post" style="display:inline">
                                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                    <button class="btn btn--danger btn--small" type="submit" data-confirm-delete>Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>