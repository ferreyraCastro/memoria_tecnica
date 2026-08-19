<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$q = trim($_GET['q'] ?? '');
$tipo = $_GET['tipo'] ?? '';

$sql = "SELECT * FROM red_dispositivos WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (nombre LIKE ? OR mac LIKE ? OR ip LIKE ? OR ssid LIKE ? OR ubicacion LIKE ?)";
    $like = "%$q%"; for ($i=0;$i<5;$i++) $params[] = $like;
}
if ($tipo !== '') { $sql .= " AND tipo = ?"; $params[] = $tipo; }
$sql .= " ORDER BY tipo, nombre";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$dispositivos = $stmt->fetchAll();

$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);

$tiposLabel = ['access_point' => 'Access Point', 'switch' => 'Switch', 'router' => 'Router', 'modem' => 'Módem', 'otro' => 'Otro'];

$pageTitle = 'Red y Wi-Fi';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-wifi"></i> Infraestructura de red y Wi-Fi</h1>
    <div class="subtitle">Access points, switches, routers y redes Wi-Fi del colegio</div>
  </div>
  <div class="d-flex gap-2">
    <a href="diagrama.php" class="btn btn-outline-secondary"><i class="bi bi-diagram-3"></i> Diagrama de red</a>
    <?php if (canEdit()): ?>
    <a href="crear.php" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Nuevo dispositivo</a>
    <?php endif; ?>
  </div>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-6">
        <label class="form-label small fw-semibold mb-1">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Nombre, MAC, IP, SSID, ubicación..." value="<?= h($q) ?>">
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
      <thead><tr><th>Tipo</th><th>Nombre</th><th>SSID</th><th>IP</th><th>MAC</th><th>Ubicación</th><th class="text-end">Acciones</th></tr></thead>
      <tbody>
      <?php if (!$dispositivos): ?>
        <tr><td colspan="7" class="empty-state"><i class="bi bi-wifi-off"></i>No se encontraron dispositivos de red</td></tr>
      <?php else: foreach ($dispositivos as $d): ?>
        <tr>
          <td><span class="badge bg-info-subtle text-info-emphasis"><?= h($tiposLabel[$d['tipo']] ?? $d['tipo']) ?></span></td>
          <td class="fw-semibold"><?= h($d['nombre']) ?></td>
          <td><?= h($d['ssid'] ?: '-') ?></td>
          <td><?= h($d['ip'] ?: '-') ?></td>
          <td class="small"><?= h($d['mac'] ?: '-') ?></td>
          <td><?= h($d['ubicacion'] ?: '-') ?><?= $d['piso'] ? ' (Piso ' . h($d['piso']) . ')' : '' ?></td>
          <td class="text-end text-nowrap">
            <a href="ver.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
            <?php if (canEdit()): ?>
            <a href="editar.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $d['id'] ?>', 'Se eliminará &quot;<?= htmlspecialchars(addslashes($d['nombre']), ENT_QUOTES) ?>&quot;.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
