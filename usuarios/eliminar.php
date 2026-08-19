<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireRole(['admin']);

$id = (int)($_GET['id'] ?? 0);
if ($id && $id !== currentUser()['id']) {
    $stmt = getDB()->prepare('DELETE FROM usuarios_sistema WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash_success'] = 'Usuario eliminado.';
} else {
    $_SESSION['flash_error'] = 'No podés eliminar tu propio usuario.';
}
redirect('index.php');
