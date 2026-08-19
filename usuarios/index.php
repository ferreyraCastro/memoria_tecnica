<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$db = getDB();
$usuarios = $db->query('SELECT * FROM usuarios_sistema ORDER BY nombre')->fetchAll();
$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);
$flashError = $_SESSION['flash_error'] ?? null; unset($_SESSION['flash_error']);

$rolLabel = ['admin' => 'Administrador', 'tecnico' => 'Técnico', 'lectura' => 'Solo lectura'];

$pageTitle = 'Usuarios del sistema';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-people"></i> Usuarios del sistema</h1>
    <div class="subtitle">Cuentas de acceso y niveles de permiso</div>
  </div>
  <a href="crear.php" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Nuevo usuario</a>
</div>

<div class="card-app">
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead><tr><th>Nombre</th><th>Usuario</th><th>Email</th><th>Rol</th><th>Estado</th><th>Último acceso</th><th class="text-end">Acciones</th></tr></thead>
      <tbody>
      <?php foreach ($usuarios as $u): ?>
        <tr>
          <td class="fw-semibold"><?= h($u['nombre']) ?></td>
          <td><?= h($u['usuario']) ?></td>
          <td><?= h($u['email']) ?></td>
          <td><span class="badge badge-rol badge-rol-<?= h($u['rol']) ?>"><?= h($rolLabel[$u['rol']] ?? $u['rol']) ?></span></td>
          <td><?= $u['activo'] ? '<span class="badge bg-success-subtle text-success-emphasis">Activo</span>' : '<span class="badge bg-secondary-subtle text-secondary-emphasis">Inactivo</span>' ?></td>
          <td><?= formatFechaHora($u['ultimo_acceso']) ?></td>
          <td class="text-end text-nowrap">
            <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <?php if ($u['id'] !== currentUser()['id']): ?>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $u['id'] ?>', 'Se eliminará el usuario &quot;<?= htmlspecialchars(addslashes($u['nombre']), ENT_QUOTES) ?>&quot;.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
