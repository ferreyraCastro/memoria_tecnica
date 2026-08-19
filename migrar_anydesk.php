<?php
/**
 * Migración única: agrega los campos de AnyDesk (número y contraseña cifrada)
 * a la tabla `equipos` ya existente, sin borrar ningún dato.
 *
 * Ejecutar UNA sola vez desde el navegador:
 *   http://localhost/memoria/migrar_anydesk.php
 * y luego borrar este archivo.
 */
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/plain; charset=utf-8');
echo "=== Migración: campos de AnyDesk en equipos ===\n\n";

try {
    $db = getDB();

    $stmt = $db->prepare("
        SELECT COLUMN_NAME FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'equipos' AND COLUMN_NAME = ?
    ");

    $columnas = [
        'anydesk_id' => "ALTER TABLE equipos ADD COLUMN anydesk_id VARCHAR(50) NULL AFTER usuario_asignado",
        'anydesk_password_cifrada' => "ALTER TABLE equipos ADD COLUMN anydesk_password_cifrada TEXT NULL AFTER anydesk_id",
        'anydesk_iv' => "ALTER TABLE equipos ADD COLUMN anydesk_iv VARCHAR(64) NULL AFTER anydesk_password_cifrada",
    ];

    foreach ($columnas as $nombre => $sql) {
        $stmt->execute([DB_NAME, $nombre]);
        if ($stmt->fetch()) {
            echo "- Columna '$nombre' ya existe, se omite.\n";
        } else {
            $db->exec($sql);
            echo "- Columna '$nombre' agregada correctamente.\n";
        }
    }

    echo "\n=== Migración completada. Ya podés borrar este archivo (migrar_anydesk.php). ===\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
