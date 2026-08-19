<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    redirect(baseUrl() . 'index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Completá usuario y contraseña.';
    } else {
        $stmt = getDB()->prepare('SELECT * FROM usuarios_sistema WHERE usuario = ? AND activo = 1 LIMIT 1');
        $stmt->execute([$usuario]);
        $row = $stmt->fetch();

        if ($row && password_verify($password, $row['password_hash'])) {
            $_SESSION['usuario'] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'usuario' => $row['usuario'],
                'rol' => $row['rol'],
            ];
            $upd = getDB()->prepare('UPDATE usuarios_sistema SET ultimo_acceso = NOW() WHERE id = ?');
            $upd->execute([$row['id']]);
            redirect(baseUrl() . 'index.php');
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ingresar · <?= h(APP_NAME) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrapper">
  <div class="login-card">
    <div class="text-center">
      <div class="brand-icon">🖥️</div>
      <h1 class="h4 fw-bold mb-1">Memoria Técnica</h1>
      <p class="text-secondary mb-4">Colegio San José</p>
    </div>
    <?php if ($error): ?>
      <div class="alert alert-danger py-2 small"><?= h($error) ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Usuario</label>
        <input type="text" name="usuario" class="form-control" autofocus required value="<?= h($_POST['usuario'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label small fw-semibold">Contraseña</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-brand w-100 mt-2">Ingresar</button>
    </form>
  </div>
</div>
</body>
</html>
