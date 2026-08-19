<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = getDB()->prepare('DELETE FROM accesos WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash_success'] = 'Acceso eliminado.';
}
redirect('index.php');
