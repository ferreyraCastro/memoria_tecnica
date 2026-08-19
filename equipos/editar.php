<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM equipos WHERE id = ?');
$stmt->execute([$id]);
$equipo = $stmt->fetch();
if (!$equipo) { $_SESSION['flash_error'] = 'Equipo no encontrado.'; redirect('index.php'); }

$errores = [];
$campos = ['nombre_pc','mac','ip','subred','tipo_conexion','aula','sala','piso','curso','sistema_operativo','usuario_asignado','claves_info','observaciones','estado'];
$form = $equipo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($campos as $c) $form[$c] = trim($_POST[$c] ?? '');
    if ($form['nombre_pc'] === '') $errores[] = 'El nombre / identificación de la PC es obligatorio.';

    if (!$errores) {
        $stmt = $db->prepare("UPDATE equipos SET nombre_pc=?, mac=?, ip=?, subred=?, tipo_conexion=?, aula=?, sala=?, piso=?, curso=?, sistema_operativo=?, usuario_asignado=?, claves_info=?, observaciones=?, estado=? WHERE id=?");
        $stmt->execute([
            $form['nombre_pc'], $form['mac'] ?: null, $form['ip'] ?: null, $form['subred'] ?: null, $form['tipo_conexion'],
            $form['aula'] ?: null, $form['sala'] ?: null, $form['piso'] ?: null, $form['curso'] ?: null,
            $form['sistema_operativo'] ?: null, $form['usuario_asignado'] ?: null, $form['claves_info'] ?: null,
            $form['observaciones'] ?: null, $form['estado'], $id,
        ]);
        $_SESSION['flash_success'] = 'Equipo actualizado correctamente.';
        redirect('ver.php?id=' . $id);
    }
}

$pageTitle = 'Editar equipo';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-pencil"></i> Editar equipo</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <input type="hidden" name="id" value="<?= $id ?>">
      <?php require __DIR__ . '/_form_fields.php'; ?>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar cambios</button>
        <a href="ver.php?id=<?= $id ?>" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
