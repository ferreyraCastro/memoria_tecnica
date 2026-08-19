<?php
$user = currentUser();
$here = basename($_SERVER['SCRIPT_NAME']);
$dir = basename(dirname($_SERVER['SCRIPT_NAME']));

function navActive(string $section, string $dir): string
{
    return $dir === $section ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <i class="bi bi-hdd-network"></i>
    <span>Memoria Técnica</span>
  </div>
  <nav class="nav flex-column sidebar-nav">
    <a class="nav-link <?= $dir === '' && ($here === 'index.php') ? 'active' : '' ?>" href="<?= baseUrl() ?>index.php">
      <i class="bi bi-speedometer2"></i> Panel principal
    </a>

    <div class="nav-section-title">Inventario</div>
    <a class="nav-link <?= navActive('equipos', $dir) ?>" href="<?= baseUrl() ?>equipos/index.php">
      <i class="bi bi-pc-display"></i> Equipos / PCs
    </a>
    <a class="nav-link <?= navActive('red', $dir) ?>" href="<?= baseUrl() ?>red/index.php">
      <i class="bi bi-wifi"></i> Red y Wi-Fi
    </a>
    <a class="nav-link <?= navActive('servicio_tecnico', $dir) ?>" href="<?= baseUrl() ?>servicio_tecnico/index.php">
      <i class="bi bi-tools"></i> Servicio Técnico
    </a>

    <div class="nav-section-title">Administración</div>
    <a class="nav-link <?= navActive('suscripciones', $dir) ?>" href="<?= baseUrl() ?>suscripciones/index.php">
      <i class="bi bi-calendar-check"></i> Suscripciones
    </a>
    <?php if (isAdmin()): ?>
    <a class="nav-link <?= navActive('accesos', $dir) ?>" href="<?= baseUrl() ?>accesos/index.php">
      <i class="bi bi-shield-lock"></i> Contraseñas y accesos
    </a>
    <?php endif; ?>
    <a class="nav-link <?= navActive('documentacion', $dir) ?>" href="<?= baseUrl() ?>documentacion/index.php">
      <i class="bi bi-folder2-open"></i> Planos y documentación
    </a>

    <?php if (isAdmin()): ?>
    <div class="nav-section-title">Sistema</div>
    <a class="nav-link <?= navActive('usuarios', $dir) ?>" href="<?= baseUrl() ?>usuarios/index.php">
      <i class="bi bi-people"></i> Usuarios del sistema
    </a>
    <?php endif; ?>
  </nav>
  <div class="sidebar-footer text-secondary small">
    Colegio San José<br>© <?= date('Y') ?>
  </div>
</aside>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
