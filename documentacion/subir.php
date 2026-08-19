<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$errores = [];
$form = ['nombre' => '', 'descripcion' => '', 'categoria_id' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['nombre'] = trim($_POST['nombre'] ?? '');
    $form['descripcion'] = trim($_POST['descripcion'] ?? '');
    $form['categoria_id'] = $_POST['categoria_id'] ?: null;

    if ($form['nombre'] === '') $errores[] = 'El nombre del documento es obligatorio.';

    if (empty($_FILES['archivo']['name'])) {
        $errores[] = 'Debés seleccionar un archivo.';
    } else {
        $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, extensionesPermitidas(), true)) {
            $errores[] = 'Tipo de archivo no permitido (' . $ext . '). Permitidos: ' . implode(', ', extensionesPermitidas());
        }
        if ($_FILES['archivo']['size'] > MAX_UPLOAD_SIZE) {
            $errores[] = 'El archivo supera el tamaño máximo permitido (' . (MAX_UPLOAD_SIZE / 1024 / 1024) . ' MB).';
        }
        if ($_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $errores[] = 'Ocurrió un error al subir el archivo.';
        }
    }

    if (!$errores) {
        if (!is_dir(UPLOADS_DIR)) mkdir(UPLOADS_DIR, 0775, true);
        $original = $_FILES['archivo']['name'];
        $nuevoNombre = nombreArchivoSeguro($original);
        $destino = UPLOADS_DIR . '/' . $nuevoNombre;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)) {
            $stmt = $db->prepare("INSERT INTO documentos (categoria_id, nombre, descripcion, archivo_path, archivo_original, tipo_archivo, tamano_bytes, subido_por)
                VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $form['categoria_id'], $form['nombre'], $form['descripcion'] ?: null, $nuevoNombre, $original,
                strtolower(pathinfo($original, PATHINFO_EXTENSION)), $_FILES['archivo']['size'], currentUser()['id'],
            ]);
            $_SESSION['flash_success'] = 'Documento subido correctamente.';
            redirect('index.php');
        } else {
            $errores[] = 'No se pudo guardar el archivo en el servidor.';
        }
    }
}

$categorias = $db->query('SELECT * FROM categorias_documentos ORDER BY nombre')->fetchAll();
$pageTitle = 'Subir documento';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header"><h1><i class="bi bi-upload"></i> Subir documento</h1></div>

<?php if ($errores): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errores as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="card-app">
  <div class="p-4">
    <form method="post" enctype="multipart/form-data" novalidate>
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label fw-semibold">Nombre del documento *</label>
          <input type="text" name="nombre" class="form-control" required value="<?= h($form['nombre']) ?>" placeholder="Ej: Plano de red - Planta baja">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Categoría</label>
          <select name="categoria_id" class="form-select">
            <option value="">Sin categoría</option>
            <?php foreach ($categorias as $c): ?><option value="<?= $c['id'] ?>" <?= (string)$form['categoria_id'] === (string)$c['id'] ? 'selected' : '' ?>><?= h($c['nombre']) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Descripción</label>
          <textarea name="descripcion" class="form-control" rows="3"><?= h($form['descripcion']) ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Archivo *</label>
          <input type="file" name="archivo" class="form-control" required>
          <div class="form-text">Formatos permitidos: <?= implode(', ', extensionesPermitidas()) ?>. Tamaño máximo: <?= MAX_UPLOAD_SIZE / 1024 / 1024 ?> MB.</div>
        </div>
      </div>
      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-brand"><i class="bi bi-upload"></i> Subir</button>
        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
