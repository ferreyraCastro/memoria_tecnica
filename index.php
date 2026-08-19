<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$db = getDB();

$totalEquipos = (int)$db->query('SELECT COUNT(*) c FROM equipos')->fetch()['c'];

$porPiso = $db->query("SELECT COALESCE(NULLIF(piso,''),'Sin definir') piso, COUNT(*) c FROM equipos GROUP BY piso ORDER BY c DESC")->fetchAll();
$porAula = $db->query("SELECT COALESCE(NULLIF(aula,''),'Sin definir') aula, COUNT(*) c FROM equipos GROUP BY aula ORDER BY c DESC LIMIT 8")->fetchAll();

$suscActivas = (int)$db->query('SELECT COUNT(*) c FROM suscripciones WHERE activo = 1')->fetch()['c'];

$todasSusc = $db->query('SELECT * FROM suscripciones WHERE activo = 1')->fetchAll();
$proximas = [];
$vencidas = [];
foreach ($todasSusc as $s) {
    $est = estadoSuscripcion($s['fecha_vencimiento']);
    if ($est['estado'] === 'proximo') $proximas[] = $s + ['_estado' => $est];
    if ($est['estado'] === 'vencido') $vencidas[] = $s + ['_estado' => $est];
}
usort($proximas, fn($a, $b) => strcmp($a['fecha_vencimiento'], $b['fecha_vencimiento']));
usort($vencidas, fn($a, $b) => strcmp($a['fecha_vencimiento'], $b['fecha_vencimiento']));

$ultimosMantenimientos = $db->query("
  SELECT m.*, e.nombre_pc FROM mantenimientos m
  JOIN equipos e ON e.id = m.equipo_id
  ORDER BY m.fecha DESC, m.id DESC LIMIT 6
")->fetchAll();

$ultimosEquipos = $db->query("SELECT * FROM equipos ORDER BY updated_at DESC LIMIT 6")->fetchAll();

$documentosRecientes = $db->query("
  SELECT d.*, c.nombre categoria FROM documentos d
  LEFT JOIN categorias_documentos c ON c.id = d.categoria_id
  ORDER BY d.fecha_subida DESC LIMIT 6
")->fetchAll();

$totalDocumentos = (int)$db->query('SELECT COUNT(*) c FROM documentos')->fetch()['c'];

$pageTitle = 'Panel principal';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1>Panel principal</h1>
    <div class="subtitle">Resumen general de la memoria técnica del colegio</div>
  </div>
</div>

<?php if (count($vencidas) > 0 || count($proximas) > 0): ?>
<div class="alert alert-warning d-flex align-items-start gap-2 mb-4" role="alert">
  <i class="bi bi-exclamation-triangle-fill fs-5"></i>
  <div>
    <strong>Atención:</strong>
    <?php if (count($vencidas) > 0): ?> hay <?= count($vencidas) ?> suscripción(es) <span class="text-danger fw-semibold">vencida(s)</span>.<?php endif; ?>
    <?php if (count($proximas) > 0): ?> hay <?= count($proximas) ?> suscripción(es) <span class="text-warning-emphasis fw-semibold">próxima(s) a vencer</span>.<?php endif; ?>
    <a href="suscripciones/index.php" class="ms-1">Ver suscripciones →</a>
  </div>
</div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-pc-display"></i></div>
      <div class="stat-value"><?= $totalEquipos ?></div>
      <div class="stat-label">Computadoras registradas</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-calendar-check"></i></div>
      <div class="stat-value"><?= $suscActivas ?></div>
      <div class="stat-label">Servicios / suscripciones activos</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fef9c3;color:#a16207;"><i class="bi bi-clock-history"></i></div>
      <div class="stat-value"><?= count($proximas) ?></div>
      <div class="stat-label">Próximas a vencer</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-octagon"></i></div>
      <div class="stat-value"><?= count($vencidas) ?></div>
      <div class="stat-label">Suscripciones vencidas</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-4">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-building"></i> Equipos por piso</div>
      <div class="p-3">
        <?php if (!$porPiso): ?>
          <div class="empty-state py-3"><i class="bi bi-inbox"></i>Sin datos</div>
        <?php else: foreach ($porPiso as $p): ?>
          <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <span><?= h($p['piso']) ?></span>
            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill"><?= $p['c'] ?></span>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-door-open"></i> Equipos por aula (top 8)</div>
      <div class="p-3">
        <?php if (!$porAula): ?>
          <div class="empty-state py-3"><i class="bi bi-inbox"></i>Sin datos</div>
        <?php else: foreach ($porAula as $a): ?>
          <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <span><?= h($a['aula']) ?></span>
            <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill"><?= $a['c'] ?></span>
          </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-folder2-open"></i> Accesos rápidos a documentación</div>
      <div class="p-3">
        <?php if (!$documentosRecientes): ?>
          <div class="empty-state py-3"><i class="bi bi-inbox"></i>Aún no hay documentos<br>
            <a href="documentacion/index.php" class="small">Subir el primero →</a></div>
        <?php else: foreach ($documentosRecientes as $d): ?>
          <a href="<?= baseUrl() ?><?= UPLOADS_URL ?>/<?= h($d['archivo_path']) ?>" target="_blank" class="d-flex justify-content-between align-items-center py-2 border-bottom text-decoration-none text-dark">
            <span><i class="bi bi-file-earmark-text me-1 text-secondary"></i><?= h($d['nombre']) ?></span>
            <span class="text-secondary small"><?= h($d['categoria'] ?? '') ?></span>
          </a>
        <?php endforeach; ?>
        <a href="documentacion/index.php" class="small d-inline-block mt-2">Ver todos (<?= $totalDocumentos ?>) →</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-tools"></i> Últimos mantenimientos realizados</div>
      <div class="table-responsive">
        <table class="table table-app mb-0">
          <thead><tr><th>Fecha</th><th>Equipo</th><th>Descripción</th><th>Técnico</th></tr></thead>
          <tbody>
          <?php if (!$ultimosMantenimientos): ?>
            <tr><td colspan="4" class="empty-state"><i class="bi bi-inbox"></i>Sin mantenimientos registrados</td></tr>
          <?php else: foreach ($ultimosMantenimientos as $m): ?>
            <tr>
              <td><?= formatFecha($m['fecha']) ?></td>
              <td><a href="equipos/ver.php?id=<?= (int)$m['equipo_id'] ?>"><?= h($m['nombre_pc']) ?></a></td>
              <td class="text-truncate" style="max-width:220px;"><?= h($m['descripcion']) ?></td>
              <td><?= h($m['tecnico']) ?></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-arrow-repeat"></i> Últimos equipos modificados</div>
      <div class="table-responsive">
        <table class="table table-app mb-0">
          <thead><tr><th>Equipo</th><th>Ubicación</th><th>Actualizado</th></tr></thead>
          <tbody>
          <?php if (!$ultimosEquipos): ?>
            <tr><td colspan="3" class="empty-state"><i class="bi bi-inbox"></i>Sin equipos registrados</td></tr>
          <?php else: foreach ($ultimosEquipos as $e): ?>
            <tr>
              <td><a href="equipos/ver.php?id=<?= (int)$e['id'] ?>"><?= h($e['nombre_pc']) ?></a></td>
              <td><?= h(trim(($e['piso'] ? 'Piso ' . $e['piso'] : '') . ' ' . $e['aula'])) ?: '-' ?></td>
              <td><?= formatFechaHora($e['updated_at']) ?></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
