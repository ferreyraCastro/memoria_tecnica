<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$q = trim($_GET['q'] ?? '');
$filtroEstado = $_GET['estado'] ?? '';

$sql = "SELECT * FROM suscripciones WHERE activo = 1";
$params = [];
if ($q !== '') {
    $sql .= " AND (proveedor LIKE ? OR servicio LIKE ?)";
    $like = "%$q%"; $params[] = $like; $params[] = $like;
}
$sql .= " ORDER BY fecha_vencimiento ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$todas = $stmt->fetchAll();

$suscripciones = [];
$countVigente = $countProximo = $countVencido = 0;
foreach ($todas as $s) {
    $est = estadoSuscripcion($s['fecha_vencimiento']);
    $s['_estado'] = $est;
    if ($est['estado'] === 'vigente') $countVigente++;
    if ($est['estado'] === 'proximo') $countProximo++;
    if ($est['estado'] === 'vencido') $countVencido++;
    if ($filtroEstado !== '' && $est['estado'] !== $filtroEstado) continue;
    $suscripciones[] = $s;
}

$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);

$pageTitle = 'Suscripciones y renovaciones';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-calendar-check"></i> Suscripciones y renovaciones</h1>
    <div class="subtitle">Hosting, dominios, licencias y otras plataformas</div>
  </div>
  <?php if (canEdit()): ?>
  <a href="crear.php" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Nueva suscripción</a>
  <?php endif; ?>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-4">
    <a href="?estado=vigente" class="text-decoration-none">
      <div class="stat-card"><div class="d-flex justify-content-between align-items-center">
        <div><div class="stat-value text-success"><?= $countVigente ?></div><div class="stat-label">🟢 Vigentes</div></div>
      </div></div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="?estado=proximo" class="text-decoration-none">
      <div class="stat-card"><div class="d-flex justify-content-between align-items-center">
        <div><div class="stat-value text-warning"><?= $countProximo ?></div><div class="stat-label">🟡 Próximas a vencer</div></div>
      </div></div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="?estado=vencido" class="text-decoration-none">
      <div class="stat-card"><div class="d-flex justify-content-between align-items-center">
        <div><div class="stat-value text-danger"><?= $countVencido ?></div><div class="stat-label">🔴 Vencidas</div></div>
      </div></div>
    </a>
  </div>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-6">
        <label class="form-label small fw-semibold mb-1">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Proveedor o servicio..." value="<?= h($q) ?>">
      </div>
      <?php if ($filtroEstado): ?><input type="hidden" name="estado" value="<?= h($filtroEstado) ?>"><?php endif; ?>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-search"></i> Buscar</button>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card-app">
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead>
        <tr><th>Proveedor</th><th>Servicio</th><th>Costo</th><th>Vencimiento</th><th>Estado</th><th>Renovación</th><th class="text-end">Acciones</th></tr>
      </thead>
      <tbody>
      <?php if (!$suscripciones): ?>
        <tr><td colspan="7" class="empty-state"><i class="bi bi-calendar-x"></i>No hay suscripciones registradas</td></tr>
      <?php else: foreach ($suscripciones as $s): ?>
        <tr>
          <td class="fw-semibold"><?= h($s['proveedor']) ?></td>
          <td><?= h($s['servicio']) ?></td>
          <td><?= formatMoneda($s['costo'], $s['moneda']) ?></td>
          <td><?= formatFecha($s['fecha_vencimiento']) ?></td>
          <td><span class="badge-estado badge-<?= $s['_estado']['estado'] ?>"><?= $s['_estado']['icon'] ?> <?= h($s['_estado']['label']) ?></span></td>
          <td class="text-capitalize"><?= h($s['periodo_renovacion']) ?></td>
          <td class="text-end text-nowrap">
            <a href="historial.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Historial"><i class="bi bi-clock-history"></i></a>
            <?php if (canEdit()): ?>
            <a href="renovar.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-success" title="Registrar renovación"><i class="bi bi-arrow-repeat"></i></a>
            <a href="editar.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $s['id'] ?>', 'Se eliminará &quot;<?= htmlspecialchars(addslashes($s['servicio']), ENT_QUOTES) ?>&quot;.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
