<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM equipos WHERE id = ?');
$stmt->execute([$id]);
$equipo = $stmt->fetch();
if (!$equipo) { $_SESSION['flash_error'] = 'Equipo no encontrado.'; redirect('index.php'); }

$mant = $db->prepare('SELECT * FROM mantenimientos WHERE equipo_id = ? ORDER BY fecha DESC, id DESC');
$mant->execute([$id]);
$mantenimientos = $mant->fetchAll();

$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);

$estadoMap = ['activo' => ['success','Activo'], 'en_reparacion' => ['warning','En reparación'], 'de_baja' => ['secondary','De baja']];
[$ec, $el] = $estadoMap[$equipo['estado']] ?? ['secondary', $equipo['estado']];

$pageTitle = $equipo['nombre_pc'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-pc-display"></i> <?= h($equipo['nombre_pc']) ?> <span class="badge bg-<?= $ec ?>-subtle text-<?= $ec ?>-emphasis ms-2"><?= h($el) ?></span></h1>
    <div class="subtitle">Historia técnica completa del equipo</div>
  </div>
  <div class="d-flex gap-2">
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    <?php if (canEdit()): ?>
    <a href="editar.php?id=<?= $id ?>" class="btn btn-brand"><i class="bi bi-pencil"></i> Editar</a>
    <?php endif; ?>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-lg-8">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-info-circle"></i> Datos técnicos</div>
      <div class="p-3">
        <div class="row g-3">
          <div class="col-md-4"><div class="text-secondary small">Dirección MAC</div><div class="fw-semibold"><?= h($equipo['mac'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">IP (DHCP)</div><div class="fw-semibold"><?= h($equipo['ip'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Subred</div><div class="fw-semibold"><?= h($equipo['subred'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Conexión</div><div class="fw-semibold"><?= $equipo['tipo_conexion'] === 'wifi' ? 'Wi-Fi' : 'Cableada' ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Piso</div><div class="fw-semibold"><?= h($equipo['piso'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Aula</div><div class="fw-semibold"><?= h($equipo['aula'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Sala</div><div class="fw-semibold"><?= h($equipo['sala'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Curso / división</div><div class="fw-semibold"><?= h($equipo['curso'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Sistema operativo</div><div class="fw-semibold"><?= h($equipo['sistema_operativo'] ?: '-') ?></div></div>
          <div class="col-md-4"><div class="text-secondary small">Usuario asignado</div><div class="fw-semibold"><?= h($equipo['usuario_asignado'] ?: '-') ?></div></div>
          <?php if ($equipo['claves_info']): ?>
          <div class="col-12"><div class="text-secondary small">Claves / información técnica</div><div class="fw-semibold" style="white-space:pre-wrap;"><?= h($equipo['claves_info']) ?></div></div>
          <?php endif; ?>
          <?php if ($equipo['observaciones']): ?>
          <div class="col-12"><div class="text-secondary small">Observaciones</div><div style="white-space:pre-wrap;"><?= h($equipo['observaciones']) ?></div></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card-app h-100">
      <div class="card-app-header"><i class="bi bi-clock"></i> Registro</div>
      <div class="p-3">
        <div class="text-secondary small">Creado</div>
        <div class="fw-semibold mb-3"><?= formatFechaHora($equipo['created_at']) ?></div>
        <div class="text-secondary small">Última actualización</div>
        <div class="fw-semibold mb-3"><?= formatFechaHora($equipo['updated_at']) ?></div>
        <div class="text-secondary small">Mantenimientos registrados</div>
        <div class="fw-semibold"><?= count($mantenimientos) ?></div>
      </div>
    </div>
  </div>
</div>

<div class="card-app">
  <div class="card-app-header d-flex justify-content-between align-items-center">
    <span><i class="bi bi-tools"></i> Historial de servicio técnico</span>
    <?php if (canEdit()): ?>
    <a href="../servicio_tecnico/crear.php?equipo_id=<?= $id ?>" class="btn btn-sm btn-brand"><i class="bi bi-plus-lg"></i> Registrar mantenimiento</a>
    <?php endif; ?>
  </div>
  <div class="table-responsive">
    <table class="table table-app mb-0">
      <thead><tr><th>Fecha</th><th>Tipo</th><th>Problema / descripción</th><th>Componentes / repuestos</th><th>Técnico</th><th class="text-end">Acciones</th></tr></thead>
      <tbody>
      <?php if (!$mantenimientos): ?>
        <tr><td colspan="6" class="empty-state"><i class="bi bi-inbox"></i>Este equipo no tiene mantenimientos registrados</td></tr>
      <?php else: foreach ($mantenimientos as $m): ?>
        <tr>
          <td><?= formatFecha($m['fecha']) ?></td>
          <td class="text-capitalize"><?= h(str_replace('_', ' ', $m['tipo'])) ?></td>
          <td><?php if ($m['problema_detectado']): ?><span class="text-secondary small">Problema:</span> <?= h($m['problema_detectado']) ?><br><?php endif; ?><?= h($m['descripcion']) ?></td>
          <td class="small"><?= h(trim(($m['componentes_reemplazados'] ?: '') . ($m['repuestos_utilizados'] ? ' / ' . $m['repuestos_utilizados'] : ''))) ?: '-' ?></td>
          <td><?= h($m['tecnico'] ?: '-') ?></td>
          <td class="text-end">
            <a href="../servicio_tecnico/ver.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
