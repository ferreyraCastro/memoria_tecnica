<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$db = getDB();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM usuarios_sistema WHERE id = ?');
$stmt->execute([$id]);
$u = $stmt->fetch();
if (!$u) { $_SESSION['flash_error'] = 'Usuario no encontrado.'; redirect('index.php'); }

$errores = [];
$form = $u;
$form['password'] = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['nombre'] = trim($_POST['nombre'] ?? '');
    $form['email'] = trim($_POST['email'] ?? '');
    $form['usuario'] = trim($_POST['usuario'] ?? '');
    $form['password'] = $_POST['password'] ?? '';
    $form['rol'] = $_POST['rol'] ?? 'tecnico';
    $form['activo'] = isset($_POST['activo']) ? 1 : 0;

    if ($form['nombre'] === '') $errores[] = 'El nombre es obligatorio.';
    if ($form['email'] === '' || !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido.';
    if ($form['usuario'] === '') $errores[] = 'El usuario es obligatorio.';
    if ($form['password'] !== '' && strlen($form['password']) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    if ($id === currentUser()['id'] && !$form['activo']) $errores[] = 'No podés desactivar tu propio usuario.';

    if (!$errores) {
        try {
            if ($form['password'] !== '') {
                $stmt = $db->prepare('UPDATE usuarios_sistema SET nombre=?, email=?, usuario=?, password_hash=?, rol=?, activo=? WHERE id=?');
                $stmt->execute([$form['nombre'], $form['email'], $form['usuario'], password_hash($form['password'], PASSWORD_DEFAULT), $form['rol'], $form['activo'], $id]);
            } else {
                $stmt = $db->prepare('UPDATE usuarios_sistema SET nombre=?, email=?, usuario=?, rol=?, activo=? WHERE id=?');
                $stmt->execute([$form['nombre'], $form['email'], $form['usuario'], $form['rol'], $form['activo'], $id]);
            }
            if ($id === currentUser()['id']) {
                $_SESSION['usuario']['nombre'] = $form['nombre'];
                $_SESSION['usuario']['usuario'] = $form['usuario'];
                $_SESSION['usuario']['rol'] = $form['rol'];
            }
            $_SESSION['flash_success'] = 'Usuario actualizado.';
            redirect('index.php');
        } catch (PDOException $e) {
            $errores[] = (str_contains($e->getMessage(), 'Duplicate')) ? 'Ya existe un usuario con ese usuario o email.' : 'Error al guardar: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Editar usuario';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-pencil"></i> Editar usuario</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
      <input type="hidden" name="id" value="<?= $id ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nombre completo *</label>
          <input type="text" name="nombre" class="form-control" required value="<?= h($form['nombre']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Email *</label>
          <input type="email" name="email" class="form-control" required value="<?= h($form['email']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Usuario *</label>
          <input type="text" name="usuario" class="form-control" required value="<?= h($form['usuario']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nueva contraseña</label>
          <div class="input-group">
            <input type="password" name="password" id="pw" class="form-control" placeholder="Dejar en blanco para no cambiar">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this,'pw')"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Rol</label>
          <select name="rol" class="form-select">
            <option value="admin" <?= $form['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
            <option value="tecnico" <?= $form['rol'] === 'tecnico' ? 'selected' : '' ?>>Técnico</option>
            <option value="lectura" <?= $form['rol'] === 'lectura' ? 'selected' : '' ?>>Solo lectura</option>
          </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input type="checkbox" name="activo" id="activo" class="form-check-input" <?= $form['activo'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="activo">Usuario activo</label>
          </div>
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
