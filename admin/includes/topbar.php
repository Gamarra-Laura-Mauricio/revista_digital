<header class="d-topbar">
  <div class="crumbs">
    <button class="hamburger" id="sidebarToggle" type="button" aria-label="Abrir navegación">☰</button>
    <?php foreach (array_map('trim', explode('|', $crumbs)) as $i => $crumb): ?>
      <?php if ($i > 0): ?><span class="crumb-sep">›</span><?php endif; ?>
      <span class="<?= $i === count(explode('|', $crumbs))-1 ? 'current' : '' ?>"><?= e($crumb) ?></span>
    <?php endforeach; ?>
  </div>
  <div class="topbar-actions">
    <div class="topbar-user">
      <div class="avatar"></div>
      <div><strong><?= e($user['nombre_completo']) ?></strong><span><?= e($user['rol']) ?></span></div>
    </div>
    <a class="icon-btn" href="logout.php" title="Cerrar sesión">↪</a>
  </div>
</header>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var b=document.getElementById('sidebarToggle'),s=document.getElementById('appSidebar');
  if(b&&s)b.addEventListener('click',function(){s.classList.toggle('is-mobile-open');});
  var av=document.querySelector('.topbar-user .avatar'); if(av) av.textContent='<?= e(strtoupper(substr($user['nombre_completo'] ?: 'U',0,2))) ?>';
});
</script>
