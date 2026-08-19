<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$db = getDB();
$errores = [];
$form = ['nombre' => '', 'email' => '', 'usuario' => '', 'password' => '', 'rol' => 'tecnico', 'activo' => 1];

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
    if (strlen($form['password']) < 6) $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    if (!in_array($form['rol'], ['admin','tecnico','lectura'], true)) $errores[] = 'Rol inválido.';

    if (!$errores) {
        try {
            $stmt = $db->prepare('INSERT INTO usuarios_sistema (nombre, email, usuario, password_hash, rol, activo) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$form['nombre'], $form['email'], $form['usuario'], password_hash($form['password'], PASSWORD_DEFAULT), $form['rol'], $form['activo']]);
            $_SESSION['flash_success'] = 'Usuario creado correctamente.';
            redirect('index.php');
        } catch (PDOException $e) {
            $errores[] = (str_contains($e->getMessage(), 'Duplicate')) ? 'Ya existe un usuario con ese usuario o email.' : 'Error al guardar: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Nuevo usuario';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-plus-lg"></i> Nuevo usuario</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" novalidate>
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
          <label class="form-label fw-semibold">Contraseña *</label>
          <div class="input-group">
            <input type="password" name="password" id="pw" class="form-control" required minlength="6">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this,'pw')"><i class="bi bi-eye"></i></button>
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Rol</label>
          <select name="rol" class="form-select">
            <option value="admin" <?= $form['rol'] === 'admin' ? 'selected' : '' ?>>Administrador (acceso total, incluye contraseñas)</option>
            <option value="tecnico" <?= $form['rol'] === 'tecnico' ? 'selected' : '' ?>>Técnico (gestión de inventario, red y servicio técnico)</option>
            <option value="lectura" <?= $form['rol'] === 'lectura' ? 'selected' : '' ?>>Solo lectura (consulta general)</option>
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
        <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
