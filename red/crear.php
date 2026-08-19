<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$errores = [];
$campos = ['tipo','nombre','mac','ip','subred','ssid','password_wifi','ubicacion','piso','marca_modelo','observaciones'];
$form = array_fill_keys($campos, '');
$form['tipo'] = 'access_point';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $c) $form[$c] = trim($_POST[$c] ?? '');
    if ($form['nombre'] === '') $errores[] = 'El nombre es obligatorio.';

    if (!$errores) {
        $encData = null; $encIv = null;
        if ($form['password_wifi'] !== '') {
            $enc = encryptString($form['password_wifi']);
            $encData = $enc['data']; $encIv = $enc['iv'];
        }
        $stmt = $db->prepare("INSERT INTO red_dispositivos (tipo, nombre, mac, ip, subred, ssid, password_wifi_cifrada, iv, ubicacion, piso, marca_modelo, observaciones)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $form['tipo'], $form['nombre'], $form['mac'] ?: null, $form['ip'] ?: null, $form['subred'] ?: null,
            $form['ssid'] ?: null, $encData, $encIv, $form['ubicacion'] ?: null, $form['piso'] ?: null,
            $form['marca_modelo'] ?: null, $form['observaciones'] ?: null,
        ]);
        $_SESSION['flash_success'] = 'Dispositivo de red registrado.';
        redirect('index.php');
    }
}

$tiposLabel = ['access_point' => 'Access Point', 'switch' => 'Switch', 'router' => 'Router', 'modem' => 'Módem', 'otro' => 'Otro'];
$pageTitle = 'Nuevo dispositivo de red';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-plus-lg"></i> Nuevo dispositivo de red</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Tipo</label>
          <select name="tipo" class="form-select">
            <?php foreach ($tiposLabel as $k => $l): ?><option value="<?= $k ?>" <?= $form['tipo'] === $k ? 'selected' : '' ?>><?= h($l) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-8">
          <label class="form-label fw-semibold">Nombre *</label>
          <input type="text" name="nombre" class="form-control" required value="<?= h($form['nombre']) ?>" placeholder="Ej: AP-Piso1-Pasillo">
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
          <input type="text" name="ubicacion" class="form-control" value="<?= h($form['ubicacion']) ?>" placeholder="Ej: Rack principal, Pasillo aulas">
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
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
