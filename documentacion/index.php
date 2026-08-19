<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$q = trim($_GET['q'] ?? '');
$catFiltro = $_GET['categoria'] ?? '';

$sql = "SELECT d.*, c.nombre categoria FROM documentos d LEFT JOIN categorias_documentos c ON c.id = d.categoria_id WHERE 1=1";
$params = [];
if ($q !== '') { $sql .= " AND (d.nombre LIKE ? OR d.descripcion LIKE ?)"; $like = "%$q%"; $params[] = $like; $params[] = $like; }
if ($catFiltro !== '') { $sql .= " AND d.categoria_id = ?"; $params[] = $catFiltro; }
$sql .= " ORDER BY d.fecha_subida DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$documentos = $stmt->fetchAll();

$categorias = $db->query('SELECT * FROM categorias_documentos ORDER BY nombre')->fetchAll();
$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);
$flashError = $_SESSION['flash_error'] ?? null; unset($_SESSION['flash_error']);

function iconoArchivo(string $tipo): string
{
    $tipo = strtolower($tipo);
    if (in_array($tipo, ['pdf'])) return 'bi-filetype-pdf text-danger';
    if (in_array($tipo, ['png','jpg','jpeg','gif','webp'])) return 'bi-file-earmark-image text-info';
    if (in_array($tipo, ['doc','docx'])) return 'bi-filetype-doc text-primary';
    if (in_array($tipo, ['xls','xlsx'])) return 'bi-filetype-xls text-success';
    if (in_array($tipo, ['zip'])) return 'bi-file-earmark-zip text-warning';
    if (in_array($tipo, ['dwg'])) return 'bi-file-earmark-ruled text-secondary';
    return 'bi-file-earmark text-secondary';
}

function formatBytes(?int $bytes): string
{
    if (!$bytes) return '-';
    $units = ['B','KB','MB','GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) { $bytes /= 1024; $i++; }
    return round($bytes, 1) . ' ' . $units[$i];
}

$pageTitle = 'Planos y documentación';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-folder2-open"></i> Planos y documentación técnica</h1>
    <div class="subtitle">Planos, esquemas, manuales y documentación del colegio</div>
  </div>
  <div class="d-flex gap-2">
    <?php if (isAdmin()): ?><a href="categorias.php" class="btn btn-outline-secondary"><i class="bi bi-tags"></i> Categorías</a><?php endif; ?>
    <?php if (canEdit()): ?><a href="subir.php" class="btn btn-brand"><i class="bi bi-upload"></i> Subir documento</a><?php endif; ?>
  </div>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-6">
        <label class="form-label small fw-semibold mb-1">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Nombre o descripción..." value="<?= h($q) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-semibold mb-1">Categoría</label>
        <select name="categoria" class="form-select">
          <option value="">Todas</option>
          <?php foreach ($categorias as $c): ?><option value="<?= $c['id'] ?>" <?= (string)$catFiltro === (string)$c['id'] ? 'selected' : '' ?>><?= h($c['nombre']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-search"></i></button>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
      </div>
    </form>
  </div>
</div>

<?php if (!$documentos): ?>
  <div class="card-app"><div class="empty-state"><i class="bi bi-folder2-open"></i>No hay documentos registrados<?php if (canEdit()): ?><br><a href="subir.php">Subir el primero →</a><?php endif; ?></div></div>
<?php else: ?>
<div class="row g-3">
  <?php foreach ($documentos as $d): ?>
    <div class="col-sm-6 col-lg-4 col-xl-3">
      <div class="card-app doc-card h-100">
        <div class="p-3 d-flex flex-column h-100">
          <div class="d-flex align-items-start justify-content-between mb-2">
            <i class="bi <?= iconoArchivo($d['tipo_archivo']) ?> doc-icon"></i>
            <span class="badge bg-light text-secondary border"><?= h($d['categoria'] ?? 'Sin categoría') ?></span>
          </div>
          <div class="fw-semibold mb-1"><?= h($d['nombre']) ?></div>
          <?php if ($d['descripcion']): ?><div class="text-secondary small mb-2 flex-grow-1"><?= h($d['descripcion']) ?></div><?php endif; ?>
          <div class="text-secondary small mt-auto mb-2"><?= formatBytes($d['tamano_bytes']) ?> · <?= formatFecha($d['fecha_subida']) ?></div>
          <div class="d-flex gap-2">
            <a href="<?= baseUrl() ?><?= UPLOADS_URL ?>/<?= h($d['archivo_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Ver</a>
            <a href="<?= baseUrl() ?><?= UPLOADS_URL ?>/<?= h($d['archivo_path']) ?>" download class="btn btn-sm btn-outline-secondary"><i class="bi bi-download"></i></a>
            <?php if (canEdit()): ?>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $d['id'] ?>', 'Se eliminará &quot;<?= htmlspecialchars(addslashes($d['nombre']), ENT_QUOTES) ?>&quot;.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
