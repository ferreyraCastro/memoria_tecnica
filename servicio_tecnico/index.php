<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$q = trim($_GET['q'] ?? '');
$tipo = $_GET['tipo'] ?? '';

$sql = "SELECT m.*, e.nombre_pc, e.aula, e.piso FROM mantenimientos m JOIN equipos e ON e.id = m.equipo_id WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (e.nombre_pc LIKE ? OR m.descripcion LIKE ? OR m.tecnico LIKE ? OR m.problema_detectado LIKE ?)";
    $like = "%$q%"; for ($i=0;$i<4;$i++) $params[] = $like;
}
if ($tipo !== '') { $sql .= " AND m.tipo = ?"; $params[] = $tipo; }
$sql .= " ORDER BY m.fecha DESC, m.id DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll();

$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);
$tiposLabel = ['mantenimiento_preventivo' => 'Mant. preventivo', 'reparacion' => 'Reparación', 'instalacion' => 'Instalación', 'otro' => 'Otro'];

$pageTitle = 'Servicio Técnico';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-tools"></i> Servicio Técnico</h1>
    <div class="subtitle">Mantenimientos, reparaciones y componentes reemplazados</div>
  </div>
  <?php if (canEdit()): ?>
  <a href="crear.php" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Registrar servicio</a>
  <?php endif; ?>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-6">
        <label class="form-label small fw-semibold mb-1">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Equipo, técnico, descripción..." value="<?= h($q) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Tipo</label>
        <select name="tipo" class="form-select">
          <option value="">Todos</option>
          <?php foreach ($tiposLabel as $k => $l): ?><option value="<?= $k ?>" <?= $tipo === $k ? 'selected' : '' ?>><?= h($l) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-search"></i></button>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card-app">
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead><tr><th>Fecha</th><th>Equipo</th><th>Tipo</th><th>Descripción</th><th>Técnico</th><th class="text-end">Acciones</th></tr></thead>
      <tbody>
      <?php if (!$registros): ?>
        <tr><td colspan="6" class="empty-state"><i class="bi bi-tools"></i>No hay registros de servicio técnico</td></tr>
      <?php else: foreach ($registros as $m): ?>
        <tr>
          <td><?= formatFecha($m['fecha']) ?></td>
          <td><a href="../equipos/ver.php?id=<?= $m['equipo_id'] ?>"><?= h($m['nombre_pc']) ?></a></td>
          <td><span class="badge bg-secondary-subtle text-secondary-emphasis"><?= h($tiposLabel[$m['tipo']] ?? $m['tipo']) ?></span></td>
          <td class="text-truncate" style="max-width:260px;"><?= h($m['descripcion']) ?></td>
          <td><?= h($m['tecnico'] ?: '-') ?></td>
          <td class="text-end text-nowrap">
            <a href="ver.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
            <?php if (canEdit()): ?>
            <a href="editar.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $m['id'] ?>', 'Se eliminará este registro de servicio técnico.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
