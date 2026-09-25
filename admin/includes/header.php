<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = $pageTitle ?? 'Panel de administración';
$active = $active ?? 'dashboard';
$crumbs = $crumbs ?? 'Administración|' . $pageTitle;
$user = current_user();
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($pageTitle) ?> · Sistema de Noticias</title>
<script>!function(){try{var t=localStorage.getItem("dash26-theme"),e=window.matchMedia("(prefers-color-scheme: dark)").matches;document.documentElement.setAttribute("data-theme",t||(e?"dark":"light"))}catch(t){document.documentElement.setAttribute("data-theme","light")}}()</script>
<script defer src="runtime.js"></script>
<script defer src="vendors.js"></script>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="app.css">
</head>
<body data-active="<?= e($active) ?>">
<div class="shell">
<?php include __DIR__ . '/sidebar.php'; ?>
<div class="main">
<?php include __DIR__ . '/topbar.php'; ?>
<main class="content">
<?php foreach (consume_flash() as $f): ?>
<div class="alert <?= e($f['type']) ?>" style="margin-bottom:18px"><div class="body"><?= e($f['message']) ?></div></div>
<?php endforeach; ?>
<!-- En includes/header.php -->

<div class="nav-group">
    <span class="nav-label">Catálogos</span>
    
    <?php if (function_exists('is_admin') && is_admin()): ?>
        <div class="nav-dropdown" style="margin-bottom: 6px;">
            <a class="nav-link <?= ($active ?? '') === 'usuarios' ? 'active' : '' ?>" href="gestion.php?mod=usuarios" style="display: flex; align-items: center; gap: 8px;">
                <svg class="icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <span>Usuarios</span>
            </a>

            <!-- Submenú desplegado para gestión de usuarios -->
            <div class="nav-sub-items" style="padding-left: 28px; margin-top: 4px; display: flex; flex-direction: column; gap: 4px;">
                <a class="nav-link" href="gestion.php?mod=usuarios" style="font-size: 13px; opacity: 0.85;">
                    • Lista de usuarios
                </a>
                <a class="nav-link" href="gestion.php?mod=usuarios&action=new" style="font-size: 13px; color: #818cf8; font-weight: 600;">
                    + Crear nuevo usuario
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>