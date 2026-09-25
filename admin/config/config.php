<?php
<?php
declare(strict_types=1);

// 1. CREDENCIALES DE BASE DE DATOS
const DB_HOST = 'sql110.infinityfree.com';
const DB_NAME = 'if0_43004905_revista_digital';
const DB_USER = 'if0_43004905';
// ¡PON AQUÍ TU NUEVA CONTRASEÑA! (Cámbiala en el panel de InfinityFree primero)
const DB_PASS = 'oYPfWnoPWjLPs'; 

// 2. URL BASE CORREGIDA
// Si tu carpeta "admin" está directamente dentro de htdocs, usa '/admin'. 
// Si los archivos del admin están sueltos en la raíz de htdocs, déjalo vacío: ''
const BASE_URL = '/admin'; 

// 3. RUTA DE UPLOADS
// Si config.php está en admin/config/, esto subirá 2 niveles hasta htdocs/uploads/
const UPLOAD_DIR = __DIR__ . '/../../uploads/';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ... (el resto de tu código de config.php se queda exactamente igual)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db(): mysqli
{
    static $connection = null;
    if ($connection === null) {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $connection->set_charset('utf8mb4');
    }
    return $connection;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_URL . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function redirect(string $path): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flash(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $items;
}

function ensure_upload_dir(string $subdir): string
{
    $dir = rtrim(UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR . trim($subdir, '/\\') . DIRECTORY_SEPARATOR;
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    return $dir;
}

function upload_file(string $field, string $subdir, array $allowedExtensions = ['jpg','jpeg','png','webp','gif','pdf','mp3','m4a','wav','ogg']): ?string
{
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('No se pudo subir el archivo.');
    }

    $original = $_FILES[$field]['name'];
    $extension = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('Formato de archivo no permitido.');
    }
    if ($_FILES[$field]['size'] > 10 * 1024 * 1024) {
        throw new RuntimeException('El archivo supera el límite de 10 MB.');
    }

    $filename = uniqid('', true) . '.' . $extension;
    $dir = ensure_upload_dir($subdir);
    $destination = $dir . $filename;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
        throw new RuntimeException('No se pudo guardar el archivo.');
    }
    return 'uploads/' . trim($subdir, '/') . '/' . $filename;
}