<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM mantenimientos WHERE id = ?');
$stmt->execute([$id]);
$m = $stmt->fetch();
if (!$m) { $_SESSION['flash_error'] = 'Registro no encontrado.'; redirect('index.php'); }

$errores = [];
$campos = ['equipo_id','fecha','tipo','problema_detectado','descripcion','componentes_reemplazados','repuestos_utilizados','tecnico','observaciones'];
$form = $m;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $c) $form[$c] = trim($_POST[$c] ?? '');
    if ($form['equipo_id'] === '') $errores[] = 'Debés seleccionar un equipo.';
    if ($form['descripcion'] === '') $errores[] = 'La descripción es obligatoria.';
    if ($form['fecha'] === '') $errores[] = 'La fecha es obligatoria.';

    if (!$errores) {
        $stmt = $db->prepare("UPDATE mantenimientos SET equipo_id=?, fecha=?, tipo=?, problema_detectado=?, descripcion=?, componentes_reemplazados=?, repuestos_utilizados=?, tecnico=?, observaciones=? WHERE id=?");
        $stmt->execute([
            $form['equipo_id'], $form['fecha'], $form['tipo'], $form['problema_detectado'] ?: null, $form['descripcion'],
            $form['componentes_reemplazados'] ?: null, $form['repuestos_utilizados'] ?: null, $form['tecnico'] ?: null, $form['observaciones'] ?: null, $id,
        ]);
        $_SESSION['flash_success'] = 'Registro actualizado.';
        redirect('ver.php?id=' . $id);
    }
}

$equipos = $db->query('SELECT id, nombre_pc, aula, piso FROM equipos ORDER BY nombre_pc')->fetchAll();
$tiposLabel = ['mantenimiento_preventivo' => 'Mantenimiento preventivo', 'reparacion' => 'Reparación', 'instalacion' => 'Instalación', 'otro' => 'Otro'];
$pageTitle = 'Editar servicio técnico';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-pencil"></i> Editar registro de servicio técnico</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Equipo *</label>
          <select name="equipo_id" class="form-select" required>
            <?php foreach ($equipos as $e): ?>
              <option value="<?= $e['id'] ?>" <?= (string)$form['equipo_id'] === (string)$e['id'] ? 'selected' : '' ?>>
                <?= h($e['nombre_pc']) ?><?= $e['aula'] ? ' — ' . h($e['aula']) : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Fecha *</label>
          <input type="date" name="fecha" class="form-control" required value="<?= h($form['fecha']) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Tipo</label>
          <select name="tipo" class="form-select">
            <?php foreach ($tiposLabel as $k => $l): ?><option value="<?= $k ?>" <?= $form['tipo'] === $k ? 'selected' : '' ?>><?= h($l) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Problema detectado</label>
          <textarea name="problema_detectado" class="form-control" rows="2"><?= h($form['problema_detectado']) ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Descripción del trabajo realizado *</label>
          <textarea name="descripcion" class="form-control" rows="3" required><?= h($form['descripcion']) ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Componentes reemplazados</label>
          <input type="text" name="componentes_reemplazados" class="form-control" value="<?= h($form['componentes_reemplazados']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Repuestos utilizados</label>
          <input type="text" name="repuestos_utilizados" class="form-control" value="<?= h($form['repuestos_utilizados']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Técnico responsable</label>
          <input type="text" name="tecnico" class="form-control" value="<?= h($form['tecnico']) ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Observaciones técnicas</label>
          <textarea name="observaciones" class="form-control" rows="2"><?= h($form['observaciones']) ?></textarea>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar cambios</button>
        <a href="ver.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
