<?php
// Requiere que la página que incluye este archivo haya definido $pageTitle (opcional)
$pageTitle = $pageTitle ?? 'Inicio';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?> · <?= h(APP_NAME) ?></title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🖥️</text></svg>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="<?= baseUrl() ?>assets/css/style.css" rel="stylesheet">
<?= $extraHead ?? '' ?>
</head>
<body class="<?= isLoggedIn() ? 'with-sidebar' : '' ?>">
<?php if (isLoggedIn()): ?>
<nav class="topbar d-flex align-items-center justify-content-between px-3">
  <button class="btn btn-sm btn-toggle-sidebar d-lg-none" id="btnToggleSidebar"><i class="bi bi-list fs-4"></i></button>
  <div class="d-flex align-items-center gap-2">
    <span class="fw-semibold d-none d-sm-inline"><?= h(APP_NAME) ?></span>
  </div>
  <div class="d-flex align-items-center gap-3">
    <span class="text-secondary small d-none d-md-inline">
      <i class="bi bi-person-circle"></i> <?= h($user['nombre']) ?>
      <span class="badge badge-rol badge-rol-<?= h($user['rol']) ?> ms-1"><?= h(ucfirst($user['rol'])) ?></span>
    </span>
    <a href="<?= baseUrl() ?>logout.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-box-arrow-right"></i> Salir</a>
  </div>
</nav>
<?php endif; ?>
