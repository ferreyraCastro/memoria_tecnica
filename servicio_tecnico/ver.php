<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT m.*, e.nombre_pc, e.aula, e.piso FROM mantenimientos m JOIN equipos e ON e.id = m.equipo_id WHERE m.id = ?');
$stmt->execute([$id]);
$m = $stmt->fetch();
if (!$m) { $_SESSION['flash_error'] = 'Registro no encontrado.'; redirect('index.php'); }

$tiposLabel = ['mantenimiento_preventivo' => 'Mantenimiento preventivo', 'reparacion' => 'Reparación', 'instalacion' => 'Instalación', 'otro' => 'Otro'];
$pageTitle = 'Servicio técnico #' . $id;
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-tools"></i> <?= h($tiposLabel[$m['tipo']] ?? $m['tipo']) ?></h1>
    <div class="subtitle">Equipo: <a href="../equipos/ver.php?id=<?= $m['equipo_id'] ?>"><?= h($m['nombre_pc']) ?></a> · <?= formatFecha($m['fecha']) ?></div>
  </div>
  <div class="d-flex gap-2">
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    <?php if (canEdit()): ?><a href="editar.php?id=<?= $id ?>" class="btn btn-brand"><i class="bi bi-pencil"></i> Editar</a><?php endif; ?>
  </div>
</div>

<div class="card-app">
  <div class="p-4">
    <div class="row g-3">
      <?php if ($m['problema_detectado']): ?>
      <div class="col-12"><div class="text-secondary small">Problema detectado</div><div style="white-space:pre-wrap;"><?= h($m['problema_detectado']) ?></div></div>
      <?php endif; ?>
      <div class="col-12"><div class="text-secondary small">Descripción del trabajo realizado</div><div style="white-space:pre-wrap;" class="fw-semibold"><?= h($m['descripcion']) ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">Componentes reemplazados</div><div class="fw-semibold"><?= h($m['componentes_reemplazados'] ?: '-') ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">Repuestos utilizados</div><div class="fw-semibold"><?= h($m['repuestos_utilizados'] ?: '-') ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">Técnico responsable</div><div class="fw-semibold"><?= h($m['tecnico'] ?: '-') ?></div></div>
      <?php if ($m['observaciones']): ?>
      <div class="col-12"><div class="text-secondary small">Observaciones</div><div style="white-space:pre-wrap;"><?= h($m['observaciones']) ?></div></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
