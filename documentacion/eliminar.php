<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin', 'tecnico']);

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM documentos WHERE id = ?');
    $stmt->execute([$id]);
    $doc = $stmt->fetch();
    if ($doc) {
        $path = UPLOADS_DIR . '/' . $doc['archivo_path'];
        if (is_file($path)) @unlink($path);
        $del = $db->prepare('DELETE FROM documentos WHERE id = ?');
        $del->execute([$id]);
        $_SESSION['flash_success'] = 'Documento eliminado.';
    }
}
redirect('index.php');
