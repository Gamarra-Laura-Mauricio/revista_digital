
<?php $active = $active ?? 'dashboard'; ?>
<aside class="sidebar" id="appSidebar">
  <div class="brand-block">
    <a href="index.php" class="brand-link">
      <img src="assets/static/images/logo-gradient.svg" alt="Sistema de Noticias" class="brand-logo">
      <div class="brand-info">
        <span class="brand-sub">Panel de administración</span>
      </div>
    </a>
  </div>

  <nav class="app-nav">
    <div class="nav-section-title">Principal</div>
    <a class="nav-link <?= $active==='dashboard'?'is-active':'' ?>" href="index.php">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      </span>
      <span>Dashboard</span>
    </a>

    <div class="nav-section-title">Contenido</div>
    <a class="nav-link <?= $active==='reportajes'?'is-active':'' ?>" href="gestion.php?mod=reportajes">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
      </span>
      <span>Reportajes</span>
    </a>
    <a class="nav-link <?= $active==='noticias'?'is-active':'' ?>" href="gestion.php?mod=noticias">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.684A1.001 1.001 0 016 13h5m0-7a1 1 0 011-1h5a2 2 0 012 2v8a2 2 0 01-2 2h-5a1 1 0 01-1-1V6z"/></svg>
      </span>
      <span>Noticias</span>
    </a>
    <a class="nav-link <?= $active==='boletines'?'is-active':'' ?>" href="gestion.php?mod=boletines">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
      </span>
      <span>Boletines</span>
    </a>
    <a class="nav-link <?= $active==='podcasts'?'is-active':'' ?>" href="gestion.php?mod=podcasts">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
      </span>
      <span>Podcasts</span>
    </a>
    <a class="nav-link <?= $active==='videos'?'is-active':'' ?>" href="gestion.php?mod=videos">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
      </span>
      <span>Videos</span>
    </a>

    <div class="nav-section-title">Catálogos</div>
    <a class="nav-link <?= $active==='autores'?'is-active':'' ?>" href="gestion.php?mod=autores">
      <span class="nav-ico">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
      </span>
      <span>Autores</span>
    </a>

    <?php 
    $rol = strtolower((string)(current_user()['rol'] ?? ''));
    if ($rol === 'admin' || $rol === 'administrador'): 
    ?>
    <div class="nav-item">
      <a class="nav-link <?= $active==='usuarios'?'is-active':'' ?>" href="gestion.php?mod=usuarios">
        <span class="nav-ico">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </span>
        <span>Usuarios</span>
      </a>
      <div style="padding-left: 34px; margin-top: 4px; display: flex; flex-direction: column; gap: 4px;">
        <a class="nav-link" href="gestion.php?mod=usuarios" style="font-size: 13px; opacity: 0.85;">
          • Lista de usuarios
        </a>
        <a class="nav-link" href="gestion.php?mod=usuarios&action=new" style="font-size: 13px; color: #818cf8; font-weight: 600;">
          + Crear nuevo usuario
        </a>
      </div>
    </div>
    <?php endif; ?>
  </nav>

  <div class="sidebar-foot">
    <div class="sidebar-user">
      <div class="avatar small-avatar"><?= e(strtoupper(substr(current_user()['nombre_completo'] ?: 'U', 0, 2))) ?></div>
      <div>
        <strong><?= e(current_user()['nombre_completo']) ?></strong>
        <small><?= e(current_user()['rol']) ?></small>
      </div>
    </div>
    <a href="logout.php" class="logout-link">
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
      <span>Cerrar sesión</span>
    </a>
  </div>

</aside>