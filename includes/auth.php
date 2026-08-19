<?php
/**
 * Control de sesión y roles.
 * Incluir siempre después de config.php y functions.php
 */

function currentUser(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['usuario']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect(baseUrl() . 'login.php');
    }
}

/**
 * Roles permitidos: 'admin', 'tecnico', 'lectura'
 * Uso: requireRole(['admin']) o requireRole(['admin','tecnico'])
 */
function requireRole(array $roles): void
{
    requireLogin();
    $user = currentUser();
    if (!in_array($user['rol'], $roles, true)) {
        http_response_code(403);
        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Acceso denegado</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head>
        <body class="d-flex align-items-center justify-content-center vh-100 bg-light">
        <div class="text-center"><h1 class="display-1">403</h1><p class="lead">No tenés permisos para acceder a esta sección.</p>
        <a href="' . baseUrl() . 'index.php" class="btn btn-primary">Volver al inicio</a></div></body></html>';
        exit;
    }
}

function isAdmin(): bool
{
    $user = currentUser();
    return $user && $user['rol'] === 'admin';
}

function canEdit(): bool
{
    $user = currentUser();
    return $user && in_array($user['rol'], ['admin', 'tecnico'], true);
}

/**
 * Calcula la URL base del sistema (para que funcione en subcarpetas de XAMPP)
 */
function baseUrl(): string
{
    static $base = null;
    if ($base === null) {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        // Ruta relativa desde la raíz del proyecto según la profundidad del script
        $root = dirname($_SERVER['SCRIPT_NAME']);
        // Buscar el directorio raíz del proyecto comparando con DOCUMENT_ROOT
        $projectRoot = str_replace('\\', '/', dirname(__DIR__));
        $docRoot = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/'));
        $rel = str_replace($docRoot, '', $projectRoot);
        $base = rtrim($rel, '/') . '/';
        if ($base === '/') $base = '/';
    }
    return $base;
}
