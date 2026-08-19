<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$errores = [];
$form = [
    'proveedor' => '', 'servicio' => '', 'costo' => '', 'moneda' => 'ARS',
    'fecha_contratacion' => '', 'fecha_vencimiento' => '', 'periodo_renovacion' => 'anual', 'observaciones' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['proveedor','servicio','costo','moneda','fecha_contratacion','fecha_vencimiento','periodo_renovacion','observaciones'] as $f) {
        $form[$f] = trim($_POST[$f] ?? '');
    }
    if ($form['proveedor'] === '') $errores[] = 'El proveedor es obligatorio.';
    if ($form['servicio'] === '') $errores[] = 'El servicio es obligatorio.';
    if ($form['fecha_vencimiento'] === '') $errores[] = 'La fecha de vencimiento es obligatoria.';

    if (!$errores) {
        $stmt = $db->prepare("INSERT INTO suscripciones (proveedor, servicio, costo, moneda, fecha_contratacion, fecha_vencimiento, periodo_renovacion, observaciones)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $form['proveedor'], $form['servicio'], $form['costo'] !== '' ? $form['costo'] : null, $form['moneda'] ?: 'ARS',
            $form['fecha_contratacion'] ?: null, $form['fecha_vencimiento'], $form['periodo_renovacion'], $form['observaciones'] ?: null,
        ]);
        $_SESSION['flash_success'] = 'Suscripción creada correctamente.';
        redirect('index.php');
    }
}

$pageTitle = 'Nueva suscripción';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-plus-lg"></i> Nueva suscripción</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Proveedor *</label>
          <input type="text" name="proveedor" class="form-control" required value="<?= h($form['proveedor']) ?>" placeholder="Ej: GoDaddy, Microsoft, Google">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Servicio *</label>
          <input type="text" name="servicio" class="form-control" required value="<?= h($form['servicio']) ?>" placeholder="Ej: Hosting cPanel, Office 365">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Costo</label>
          <input type="number" step="0.01" name="costo" class="form-control" value="<?= h($form['costo']) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Moneda</label>
          <input type="text" name="moneda" class="form-control" value="<?= h($form['moneda'] ?: 'ARS') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Fecha de contratación</label>
          <input type="date" name="fecha_contratacion" class="form-control" value="<?= h($form['fecha_contratacion']) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Fecha de vencimiento *</label>
          <input type="date" name="fecha_vencimiento" class="form-control" required value="<?= h($form['fecha_vencimiento']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Período de renovación</label>
          <select name="periodo_renovacion" class="form-select">
            <?php foreach (['mensual','trimestral','semestral','anual','bianual','otro'] as $p): ?>
              <option value="<?= $p ?>" <?= $form['periodo_renovacion'] === $p ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
            <?php endforeach; ?>
          </select>
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
