<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$db = getDB();
$errores = [];
$form = [
    'servicio' => '', 'usuario' => '', 'password' => '', 'url' => '',
    'categoria_id' => '', 'observaciones' => '', 'fecha_actualizacion' => date('Y-m-d'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['servicio'] = trim($_POST['servicio'] ?? '');
    $form['usuario'] = trim($_POST['usuario'] ?? '');
    $form['password'] = $_POST['password'] ?? '';
    $form['url'] = trim($_POST['url'] ?? '');
    $form['categoria_id'] = $_POST['categoria_id'] ?: null;
    $form['observaciones'] = trim($_POST['observaciones'] ?? '');
    $form['fecha_actualizacion'] = $_POST['fecha_actualizacion'] ?: date('Y-m-d');

    if ($form['servicio'] === '') $errores[] = 'El servicio es obligatorio.';
    if ($form['usuario'] === '') $errores[] = 'El usuario es obligatorio.';
    if ($form['password'] === '') $errores[] = 'La contraseña es obligatoria.';

    if (!$errores) {
        $enc = encryptString($form['password']);
        $stmt = $db->prepare("INSERT INTO accesos (servicio, usuario, password_cifrada, iv, url, categoria_id, observaciones, fecha_actualizacion, creado_por)
            VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $form['servicio'], $form['usuario'], $enc['data'], $enc['iv'], $form['url'] ?: null,
            $form['categoria_id'], $form['observaciones'] ?: null, $form['fecha_actualizacion'],
            currentUser()['id'],
        ]);
        $_SESSION['flash_success'] = 'Acceso creado correctamente.';
        redirect('index.php');
    }
}

$categorias = $db->query('SELECT * FROM categorias_accesos ORDER BY nombre')->fetchAll();
$pageTitle = 'Nuevo acceso';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <h1><i class="bi bi-plus-lg"></i> Nuevo acceso</h1>
</div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Servicio *</label>
          <input type="text" name="servicio" class="form-control" required value="<?= h($form['servicio']) ?>" placeholder="Ej: Panel de hosting cPanel">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Categoría</label>
          <select name="categoria_id" class="form-select">
            <option value="">Sin categoría</option>
            <?php foreach ($categorias as $c): ?>
              <option value="<?= $c['id'] ?>" <?= (string)$form['categoria_id'] === (string)$c['id'] ? 'selected' : '' ?>><?= h($c['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Usuario *</label>
          <input type="text" name="usuario" class="form-control" required value="<?= h($form['usuario']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Contraseña *</label>
          <div class="input-group">
            <input type="password" name="password" id="passField" class="form-control" required value="<?= h($form['password']) ?>">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this,'passField')"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div class="col-md-8">
          <label class="form-label fw-semibold">URL</label>
          <input type="text" name="url" class="form-control" value="<?= h($form['url']) ?>" placeholder="https://...">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Fecha de actualización</label>
          <input type="date" name="fecha_actualizacion" class="form-control" value="<?= h($form['fecha_actualizacion']) ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Observaciones</label>
          <textarea name="observaciones" class="form-control" rows="3"><?= h($form['observaciones']) ?></textarea>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
