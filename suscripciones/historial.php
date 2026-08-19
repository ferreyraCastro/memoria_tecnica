<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM suscripciones WHERE id = ?');
$stmt->execute([$id]);
$s = $stmt->fetch();
if (!$s) { $_SESSION['flash_error'] = 'Suscripción no encontrada.'; redirect('index.php'); }

$hist = $db->prepare('SELECT h.*, u.nombre usuario_nombre FROM renovaciones_historial h LEFT JOIN usuarios_sistema u ON u.id = h.usuario_id WHERE suscripcion_id = ? ORDER BY fecha_renovacion DESC');
$hist->execute([$id]);
$historial = $hist->fetchAll();

$pageTitle = 'Historial de renovaciones';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-clock-history"></i> Historial de renovaciones</h1>
    <div class="subtitle"><?= h($s['proveedor']) ?> · <?= h($s['servicio']) ?></div>
  </div>
  <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card-app">
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead><tr><th>Fecha renovación</th><th>Vencimiento anterior</th><th>Nuevo vencimiento</th><th>Costo</th><th>Registrado por</th><th>Observaciones</th></tr></thead>
      <tbody>
      <?php if (!$historial): ?>
        <tr><td colspan="6" class="empty-state"><i class="bi bi-inbox"></i>Todavía no se registraron renovaciones para este servicio</td></tr>
      <?php else: foreach ($historial as $h): ?>
        <tr>
          <td><?= formatFecha($h['fecha_renovacion']) ?></td>
          <td><?= formatFecha($h['fecha_vencimiento_anterior']) ?></td>
          <td class="fw-semibold"><?= formatFecha($h['fecha_vencimiento_nueva']) ?></td>
          <td><?= formatMoneda($h['costo'], $s['moneda']) ?></td>
          <td><?= h($h['usuario_nombre'] ?? '-') ?></td>
          <td><?= h($h['observaciones']) ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
