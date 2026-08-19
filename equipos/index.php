<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$q = trim($_GET['q'] ?? '');
$piso = $_GET['piso'] ?? '';
$aula = $_GET['aula'] ?? '';

$sql = "SELECT * FROM equipos WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (nombre_pc LIKE ? OR mac LIKE ? OR ip LIKE ? OR curso LIKE ? OR aula LIKE ? OR usuario_asignado LIKE ?)";
    $like = "%$q%";
    for ($i = 0; $i < 6; $i++) $params[] = $like;
}
if ($piso !== '') { $sql .= " AND piso = ?"; $params[] = $piso; }
if ($aula !== '') { $sql .= " AND aula = ?"; $params[] = $aula; }
$sql .= " ORDER BY piso, aula, nombre_pc";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$equipos = $stmt->fetchAll();

$pisos = $db->query("SELECT DISTINCT piso FROM equipos WHERE piso IS NOT NULL AND piso <> '' ORDER BY piso")->fetchAll(PDO::FETCH_COLUMN);
$aulas = $db->query("SELECT DISTINCT aula FROM equipos WHERE aula IS NOT NULL AND aula <> '' ORDER BY aula")->fetchAll(PDO::FETCH_COLUMN);

$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);

$pageTitle = 'Equipos / PCs';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-pc-display"></i> Inventario de equipos</h1>
    <div class="subtitle"><?= count($equipos) ?> equipo(s) encontrado(s)</div>
  </div>
  <?php if (canEdit()): ?>
  <a href="crear.php" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Nuevo equipo</a>
  <?php endif; ?>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-5">
        <label class="form-label small fw-semibold mb-1">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Nombre, MAC, IP, curso, aula, usuario..." value="<?= h($q) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Piso</label>
        <select name="piso" class="form-select">
          <option value="">Todos</option>
          <?php foreach ($pisos as $p): ?><option value="<?= h($p) ?>" <?= $piso === $p ? 'selected' : '' ?>><?= h($p) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-semibold mb-1">Aula</label>
        <select name="aula" class="form-select">
          <option value="">Todas</option>
          <?php foreach ($aulas as $a): ?><option value="<?= h($a) ?>" <?= $aula === $a ? 'selected' : '' ?>><?= h($a) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-search"></i></button>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card-app">
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead>
        <tr><th>Equipo</th><th>Ubicación</th><th>IP</th><th>MAC</th><th>Conexión</th><th>SO</th><th>Usuario</th><th>Estado</th><th class="text-end">Acciones</th></tr>
      </thead>
      <tbody>
      <?php if (!$equipos): ?>
        <tr><td colspan="9" class="empty-state"><i class="bi bi-pc-display"></i>No se encontraron equipos</td></tr>
      <?php else: foreach ($equipos as $e): ?>
        <tr>
          <td class="fw-semibold"><a href="ver.php?id=<?= $e['id'] ?>"><?= h($e['nombre_pc']) ?></a></td>
          <td><?= h(trim(($e['piso'] ? 'Piso ' . $e['piso'] . ' · ' : '') . ($e['aula'] ?: $e['sala']))) ?: '-' ?><?php if ($e['curso']): ?><br><span class="text-secondary small"><?= h($e['curso']) ?></span><?php endif; ?></td>
          <td><?= h($e['ip'] ?: '-') ?></td>
          <td class="small"><?= h($e['mac'] ?: '-') ?></td>
          <td><?= $e['tipo_conexion'] === 'wifi' ? '<i class="bi bi-wifi"></i> Wi-Fi' : '<i class="bi bi-ethernet"></i> Cableada' ?></td>
          <td><?= h($e['sistema_operativo'] ?: '-') ?></td>
          <td><?= h($e['usuario_asignado'] ?: '-') ?></td>
          <td>
            <?php
              $estadoMap = ['activo' => ['success','Activo'], 'en_reparacion' => ['warning','En reparación'], 'de_baja' => ['secondary','De baja']];
              [$c, $l] = $estadoMap[$e['estado']] ?? ['secondary', $e['estado']];
            ?>
            <span class="badge bg-<?= $c ?>-subtle text-<?= $c ?>-emphasis"><?= h($l) ?></span>
          </td>
          <td class="text-end text-nowrap">
            <a href="ver.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
            <?php if (canEdit()): ?>
            <a href="editar.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $e['id'] ?>', 'Se eliminará el equipo &quot;<?= htmlspecialchars(addslashes($e['nombre_pc']), ENT_QUOTES) ?>&quot; y su historial.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
