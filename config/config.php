<?php
/**
 * Configuración general del sistema
 * Memoria Técnica Digital - Colegio San José
 */

// ---- Datos de conexión a la base de datos ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'memoria_tecnica');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---- Clave de cifrado para contraseñas guardadas (AES-256-CBC) ----
// IMPORTANTE: cambiar esta clave por una propia antes de usar en producción
// y no perderla, ya que sin ella no se pueden descifrar las contraseñas guardadas.
define('APP_ENCRYPTION_KEY', 'CAMBIAR-ESTA-CLAVE-POR-UNA-PROPIA-32BYTES!!');

// ---- Nombre del sistema ----
define('APP_NAME', 'Memoria Técnica - Colegio San José');

// ---- Carpeta de subida de documentos ----
define('UPLOADS_DIR', __DIR__ . '/../uploads/documentos');
define('UPLOADS_URL', 'uploads/documentos');
define('MAX_UPLOAD_SIZE', 25 * 1024 * 1024); // 25 MB

// ---- Zona horaria ----
date_default_timezone_set('America/Argentina/Buenos_Aires');

// ---- Sesión ----
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- Conexión PDO ----
function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Error de conexión a la base de datos: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}
