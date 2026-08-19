<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$db = getDB();
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre'])) {
        $nombre = trim($_POST['nombre']);
        if ($nombre === '') {
            $errores[] = 'El nombre de la categoría es obligatorio.';
        } else {
            try {
                $db->prepare('INSERT INTO categorias_documentos (nombre) VALUES (?)')->execute([$nombre]);
                $_SESSION['flash_success'] = 'Categoría creada.';
                redirect('categorias.php');
            } catch (PDOException $e) {
                $errores[] = 'Ya existe una categoría con ese nombre.';
            }
        }
    }
}

if (isset($_GET['eliminar'])) {
    $db->prepare('DELETE FROM categorias_documentos WHERE id = ?')->execute([(int)$_GET['eliminar']]);
    $_SESSION['flash_success'] = 'Categoría eliminada.';
    redirect('categorias.php');
}

if (isset($_GET['editar']) && isset($_GET['nombre'])) {
    $nuevoNombre = trim($_GET['nombre']);
    if ($nuevoNombre === '') {
        $errores[] = 'El nombre de la categoría es obligatorio.';
    } else {
        try {
            $db->prepare('UPDATE categorias_documentos SET nombre = ? WHERE id = ?')->execute([$nuevoNombre, (int)$_GET['editar']]);
            $_SESSION['flash_success'] = 'Categoría actualizada.';
            redirect('categorias.php');
        } catch (PDOException $e) {
            $errores[] = 'Ya existe una categoría con ese nombre.';
        }
    }
}

$categorias = $db->query('SELECT c.*, (SELECT COUNT(*) FROM documentos d WHERE d.categoria_id = c.id) usados FROM categorias_documentos c ORDER BY nombre')->fetchAll();
$flashSuccess = $_SESSION['flash_success'] ?? null; unset($_SESSION['flash_success']);

$pageTitle = 'Categorías de documentos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div><h1><i class="bi bi-tags"></i> Categorías de documentación</h1></div>
  <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<?php if ($errores): ?><div class="alert alert-danger"><?php foreach ($errores as $e): ?><?= h($e) ?><?php endforeach; ?></div><?php endif; ?>

<div class="row g-3">
  <div class="col-md-5">
    <div class="card-app">
      <div class="card-app-header">Nueva categoría</div>
      <div class="p-3">
        <form method="post" class="d-flex gap-2">
          <input type="text" name="nombre" class="form-control" placeholder="Nombre de categoría" required>
          <button class="btn btn-brand"><i class="bi bi-plus-lg"></i></button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card-app">
      <div class="table-responsive">
        <table class="table table-app mb-0">
          <thead><tr><th>Nombre</th><th>Documentos</th><th class="text-end">Acciones</th></tr></thead>
          <tbody>
          <?php foreach ($categorias as $c): ?>
            <tr>
              <td><?= h($c['nombre']) ?></td>
              <td><?= (int)$c['usados'] ?></td>
              <td class="text-end">
                <a href="#" onclick="return editarNombre('?editar=<?= $c['id'] ?>', '<?= htmlspecialchars(addslashes($c['nombre']), ENT_QUOTES) ?>')" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                <a href="#" onclick="return confirmarBorrado('?eliminar=<?= $c['id'] ?>', 'Se eliminará la categoría &quot;<?= htmlspecialchars(addslashes($c['nombre']), ENT_QUOTES) ?>&quot;.')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
