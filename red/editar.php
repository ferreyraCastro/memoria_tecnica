<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM red_dispositivos WHERE id = ?');
$stmt->execute([$id]);
$d = $stmt->fetch();
if (!$d) { $_SESSION['flash_error'] = 'Dispositivo no encontrado.'; redirect('index.php'); }

$errores = [];
$campos = ['tipo','nombre','mac','ip','subred','ssid','ubicacion','piso','marca_modelo','observaciones'];
$form = $d;
$form['password_wifi'] = $d['password_wifi_cifrada'] ? decryptString($d['password_wifi_cifrada'], $d['iv']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $c) $form[$c] = trim($_POST[$c] ?? '');
    $form['password_wifi'] = trim($_POST['password_wifi'] ?? '');
    if ($form['nombre'] === '') $errores[] = 'El nombre es obligatorio.';

    if (!$errores) {
        $encData = $d['password_wifi_cifrada']; $encIv = $d['iv'];
        if ($form['password_wifi'] !== '') {
            $enc = encryptString($form['password_wifi']);
            $encData = $enc['data']; $encIv = $enc['iv'];
        }
        $stmt = $db->prepare("UPDATE red_dispositivos SET tipo=?, nombre=?, mac=?, ip=?, subred=?, ssid=?, password_wifi_cifrada=?, iv=?, ubicacion=?, piso=?, marca_modelo=?, observaciones=? WHERE id=?");
        $stmt->execute([
            $form['tipo'], $form['nombre'], $form['mac'] ?: null, $form['ip'] ?: null, $form['subred'] ?: null,
            $form['ssid'] ?: null, $encData, $encIv, $form['ubicacion'] ?: null, $form['piso'] ?: null,
            $form['marca_modelo'] ?: null, $form['observaciones'] ?: null, $id,
        ]);
        $_SESSION['flash_success'] = 'Dispositivo actualizado.';
        redirect('index.php');
    }
}

$tiposLabel = ['access_point' => 'Access Point', 'switch' => 'Switch', 'router' => 'Router', 'modem' => 'Módem', 'otro' => 'Otro'];
$pageTitle = 'Editar dispositivo de red';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-pencil"></i> Editar dispositivo de red</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tipo</label>
          <select name="tipo" class="form-select">
            <?php foreach ($tiposLabel as $k => $l): ?><option value="<?= $k ?>" <?= $form['tipo'] === $k ? 'selected' : '' ?>><?= h($l) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-8">
          <label class="form-label fw-semibold">Nombre *</label>
          <input type="text" name="nombre" class="form-control" required value="<?= h($form['nombre']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Dirección MAC</label>
          <input type="text" name="mac" class="form-control" value="<?= h($form['mac']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">IP</label>
          <input type="text" name="ip" class="form-control" value="<?= h($form['ip']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Subred</label>
          <input type="text" name="subred" class="form-control" value="<?= h($form['subred']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">SSID (red Wi-Fi)</label>
          <input type="text" name="ssid" class="form-control" value="<?= h($form['ssid']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Contraseña Wi-Fi</label>
          <div class="input-group">
            <input type="password" name="password_wifi" id="pwWifi" class="form-control" value="<?= h($form['password_wifi']) ?>">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this,'pwWifi')"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Ubicación</label>
          <input type="text" name="ubicacion" class="form-control" value="<?= h($form['ubicacion']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Piso</label>
          <input type="text" name="piso" class="form-control" value="<?= h($form['piso']) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Marca / modelo</label>
          <input type="text" name="marca_modelo" class="form-control" value="<?= h($form['marca_modelo']) ?>">
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Observaciones / datos técnicos</label>
          <textarea name="observaciones" class="form-control" rows="3"><?= h($form['observaciones']) ?></textarea>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar cambios</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
