<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$errores = [];
$campos = ['nombre_pc','mac','ip','subred','tipo_conexion','aula','sala','piso','curso','sistema_operativo','usuario_asignado','anydesk_id','claves_info','observaciones','estado'];
$form = array_fill_keys($campos, '');
$form['anydesk_password'] = '';
$form['tipo_conexion'] = 'cableada';
$form['estado'] = 'activo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $c) $form[$c] = trim($_POST[$c] ?? '');
    $form['anydesk_password'] = $_POST['anydesk_password'] ?? '';
    if ($form['nombre_pc'] === '') $errores[] = 'El nombre / identificación de la PC es obligatorio.';

    if (!$errores) {
        if ($form['anydesk_password'] !== '') {
            $encAny = encryptString($form['anydesk_password']);
            $anydeskCifrada = $encAny['data'];
            $anydeskIv = $encAny['iv'];
        } else {
            $anydeskCifrada = null;
            $anydeskIv = null;
        }
        $stmt = $db->prepare("INSERT INTO equipos (nombre_pc, mac, ip, subred, tipo_conexion, aula, sala, piso, curso, sistema_operativo, usuario_asignado, anydesk_id, anydesk_password_cifrada, anydesk_iv, claves_info, observaciones, estado)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $form['nombre_pc'], $form['mac'] ?: null, $form['ip'] ?: null, $form['subred'] ?: null, $form['tipo_conexion'],
            $form['aula'] ?: null, $form['sala'] ?: null, $form['piso'] ?: null, $form['curso'] ?: null,
            $form['sistema_operativo'] ?: null, $form['usuario_asignado'] ?: null, $form['anydesk_id'] ?: null,
            $anydeskCifrada, $anydeskIv, $form['claves_info'] ?: null,
            $form['observaciones'] ?: null, $form['estado'],
        ]);
        $_SESSION['flash_success'] = 'Equipo registrado correctamente.';
        redirect('index.php');
    }
}

$pageTitle = 'Nuevo equipo';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-plus-lg"></i> Nuevo equipo</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <?php require __DIR__ . '/_form_fields.php'; ?>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
