<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$db = getDB();
$q = trim($_GET['q'] ?? '');
$catFiltro = $_GET['categoria'] ?? '';

$sql = "SELECT a.*, c.nombre categoria FROM accesos a LEFT JOIN categorias_accesos c ON c.id = a.categoria_id WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (a.servicio LIKE ? OR a.usuario LIKE ? OR a.url LIKE ?)";
    $like = "%$q%";
    $params[] = $like; $params[] = $like; $params[] = $like;
}
if ($catFiltro !== '') {
    $sql .= " AND a.categoria_id = ?";
    $params[] = $catFiltro;
}
$sql .= " ORDER BY a.servicio ASC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$accesos = $stmt->fetchAll();

$categorias = $db->query('SELECT * FROM categorias_accesos ORDER BY nombre')->fetchAll();

$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);

$pageTitle = 'Contraseñas y accesos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-shield-lock"></i> Contraseñas y accesos</h1>
    <div class="subtitle">Usuarios, servicios y credenciales de la institución — solo administradores</div>
  </div>
  <div class="d-flex gap-2">
    <a href="categorias.php" class="btn btn-outline-secondary"><i class="bi bi-tags"></i> Categorías</a>
    <a href="crear.php" class="btn btn-brand"><i class="bi bi-plus-lg"></i> Nuevo acceso</a>
  </div>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <form class="row g-2 align-items-end" method="get">
      <div class="col-md-5">
        <label class="form-label small fw-semibold mb-1">Buscar</label>
        <input type="text" name="q" class="form-control" placeholder="Servicio, usuario o URL..." value="<?= h($q) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-semibold mb-1">Categoría</label>
        <select name="categoria" class="form-select">
          <option value="">Todas</option>
          <?php foreach ($categorias as $c): ?>
            <option value="<?= $c['id'] ?>" <?= (string)$catFiltro === (string)$c['id'] ? 'selected' : '' ?>><?= h($c['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-outline-secondary flex-fill"><i class="bi bi-search"></i> Filtrar</button>
        <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card-app">
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead>
        <tr>
          <th>Servicio</th><th>Usuario</th><th>Contraseña</th><th>Categoría</th><th>URL</th><th>Actualizado</th><th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!$accesos): ?>
        <tr><td colspan="7" class="empty-state"><i class="bi bi-shield-lock"></i>No se encontraron accesos registrados</td></tr>
      <?php else: foreach ($accesos as $a):
        $plain = decryptString($a['password_cifrada'], $a['iv']);
        $fid = 'pw_' . $a['id'];
      ?>
        <tr>
          <td class="fw-semibold"><?= h($a['servicio']) ?></td>
          <td><?= h($a['usuario']) ?>
            <button class="btn btn-sm btn-link p-0 ms-1" title="Copiar usuario" onclick="copiarTexto('<?= htmlspecialchars(addslashes($a['usuario']), ENT_QUOTES) ?>')"><i class="bi bi-clipboard"></i></button>
          </td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <input type="password" readonly id="<?= $fid ?>" value="<?= h($plain) ?>" class="form-control form-control-sm password-field" style="width:150px;">
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePassword(this, '<?= $fid ?>')"><i class="bi bi-eye"></i></button>
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copiarTexto('<?= htmlspecialchars(addslashes($plain), ENT_QUOTES) ?>')" title="Copiar contraseña"><i class="bi bi-clipboard"></i></button>
            </div>
          </td>
          <td><?= h($a['categoria'] ?? '-') ?></td>
          <td><?php if ($a['url']): ?><a href="<?= h($a['url']) ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i></a><?php else: ?>-<?php endif; ?></td>
          <td><?= formatFecha($a['fecha_actualizacion']) ?></td>
          <td class="text-end text-nowrap">
            <a href="editar.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            <a href="#" onclick="return confirmarBorrado('eliminar.php?id=<?= $a['id'] ?>', 'Se eliminará el acceso a &quot;<?= htmlspecialchars(addslashes($a['servicio']), ENT_QUOTES) ?>&quot;.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
