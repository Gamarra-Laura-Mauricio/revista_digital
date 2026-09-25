<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function current_user(): array
{
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'nombre_completo' => $_SESSION['user_nombre'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'rol' => $_SESSION['user_rol'] ?? '',
    ];
}

function is_admin(): bool
{
    $rol = strtolower((string)($_SESSION['user_rol'] ?? ''));
    return in_array($rol, ['admin', 'administrador'], true);
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        flash('danger', 'No tienes permisos de administrador para esta sección.');
        redirect('index.php');
    }
}