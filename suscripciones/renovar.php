<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM suscripciones WHERE id = ?');
$stmt->execute([$id]);
$s = $stmt->fetch();
if (!$s) { $_SESSION['flash_error'] = 'Suscripción no encontrada.'; redirect('index.php'); }

$errores = [];
$nuevaFecha = '';
$costo = $s['costo'];
$observaciones = '';

// Sugerir próxima fecha según período
function sugerirProximoVencimiento(string $fechaActual, string $periodo): string
{
    $d = new DateTime($fechaActual);
    switch ($periodo) {
        case 'mensual': $d->modify('+1 month'); break;
        case 'trimestral': $d->modify('+3 months'); break;
        case 'semestral': $d->modify('+6 months'); break;
        case 'anual': $d->modify('+1 year'); break;
        case 'bianual': $d->modify('+2 years'); break;
        default: $d->modify('+1 year');
    }
    return $d->format('Y-m-d');
}

$sugerida = sugerirProximoVencimiento($s['fecha_vencimiento'], $s['periodo_renovacion']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaFecha = $_POST['fecha_vencimiento_nueva'] ?? '';
    $costo = trim($_POST['costo'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    if ($nuevaFecha === '') $errores[] = 'La nueva fecha de vencimiento es obligatoria.';

    if (!$errores) {
        $db->beginTransaction();
        $insHist = $db->prepare("INSERT INTO renovaciones_historial (suscripcion_id, fecha_renovacion, fecha_vencimiento_anterior, fecha_vencimiento_nueva, costo, observaciones, usuario_id)
            VALUES (?,?,?,?,?,?,?)");
        $insHist->execute([$id, date('Y-m-d'), $s['fecha_vencimiento'], $nuevaFecha, $costo !== '' ? $costo : null, $observaciones ?: null, currentUser()['id']]);

        $upd = $db->prepare("UPDATE suscripciones SET fecha_vencimiento = ?, costo = ? WHERE id = ?");
        $upd->execute([$nuevaFecha, $costo !== '' ? $costo : $s['costo'], $id]);
        $db->commit();

        $_SESSION['flash_success'] = 'Renovación registrada. Nuevo vencimiento: ' . formatFecha($nuevaFecha);
        redirect('index.php');
    }
}

$pageTitle = 'Registrar renovación';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-arrow-repeat"></i> Registrar renovación</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <div class="alert alert-light border mb-4">
      <strong><?= h($s['proveedor']) ?> · <?= h($s['servicio']) ?></strong><br>
      Vencimiento actual: <span class="fw-semibold"><?= formatFecha($s['fecha_vencimiento']) ?></span>
    </div>
    <form method="post" novalidate>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nueva fecha de vencimiento *</label>
          <input type="date" name="fecha_vencimiento_nueva" class="form-control" required value="<?= h($nuevaFecha ?: $sugerida) ?>">
          <div class="form-text">Sugerida automáticamente según el período de renovación (<?= h($s['periodo_renovacion']) ?>).</div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Costo de la renovación</label>
          <input type="number" step="0.01" name="costo" class="form-control" value="<?= h($costo) ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Observaciones</label>
          <textarea name="observaciones" class="form-control" rows="3" placeholder="Ej: pagado por transferencia, factura N°..."><?= h($observaciones) ?></textarea>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Confirmar renovación</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
