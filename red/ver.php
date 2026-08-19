<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM red_dispositivos WHERE id = ?');
$stmt->execute([$id]);
$d = $stmt->fetch();
if (!$d) { $_SESSION['flash_error'] = 'Dispositivo no encontrado.'; redirect('index.php'); }

$tiposLabel = ['access_point' => 'Access Point', 'switch' => 'Switch', 'router' => 'Router', 'modem' => 'Módem', 'otro' => 'Otro'];
$pageTitle = $d['nombre'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-router"></i> <?= h($d['nombre']) ?></h1>
    <div class="subtitle"><?= h($tiposLabel[$d['tipo']] ?? $d['tipo']) ?></div>
  </div>
  <div class="d-flex gap-2">
    <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    <?php if (canEdit()): ?><a href="editar.php?id=<?= $id ?>" class="btn btn-brand"><i class="bi bi-pencil"></i> Editar</a><?php endif; ?>
  </div>
</div>

<div class="card-app">
  <div class="p-4">
    <div class="row g-3">
      <div class="col-md-4"><div class="text-secondary small">MAC</div><div class="fw-semibold"><?= h($d['mac'] ?: '-') ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">IP</div><div class="fw-semibold"><?= h($d['ip'] ?: '-') ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">Subred</div><div class="fw-semibold"><?= h($d['subred'] ?: '-') ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">SSID</div><div class="fw-semibold"><?= h($d['ssid'] ?: '-') ?></div></div>
      <div class="col-md-4">
        <div class="text-secondary small">Contraseña Wi-Fi</div>
        <?php if ($d['password_wifi_cifrada']): $plain = decryptString($d['password_wifi_cifrada'], $d['iv']); ?>
        <div class="d-flex align-items-center gap-1">
          <input type="password" readonly id="pwView" value="<?= h($plain) ?>" class="form-control form-control-sm password-field" style="width:150px;">
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="togglePassword(this,'pwView')"><i class="bi bi-eye"></i></button>
        </div>
        <?php else: ?><div class="fw-semibold">-</div><?php endif; ?>
      </div>
      <div class="col-md-4"><div class="text-secondary small">Ubicación</div><div class="fw-semibold"><?= h($d['ubicacion'] ?: '-') ?> <?= $d['piso'] ? '(Piso ' . h($d['piso']) . ')' : '' ?></div></div>
      <div class="col-md-4"><div class="text-secondary small">Marca / modelo</div><div class="fw-semibold"><?= h($d['marca_modelo'] ?: '-') ?></div></div>
      <?php if ($d['observaciones']): ?>
      <div class="col-12"><div class="text-secondary small">Observaciones</div><div style="white-space:pre-wrap;"><?= h($d['observaciones']) ?></div></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
