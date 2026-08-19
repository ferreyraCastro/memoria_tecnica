<?php
/**
 * Migración única: agrega la columna `equipo_id` a `red_nodos` para poder
 * vincular un dispositivo del diagrama de red con su registro real en
 * Equipos / PCs (así el diagrama muestra siempre el nombre, la IP y el
 * tipo de conexión (Wi-Fi / cableada) actualizados del inventario).
 *
 * Ejecutar UNA sola vez desde el navegador:
 *   http://localhost/memoria/migrar_vinculo_equipos.php
 * y luego borrar este archivo.
 */
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/plain; charset=utf-8');
echo "=== Migración: vínculo diagrama de red <-> Equipos/PCs ===\n\n";

try {
    $db = getDB();

    $stmt = $db->prepare("
        SELECT COLUMN_NAME FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'red_nodos' AND COLUMN_NAME = 'equipo_id'
    ");
    $stmt->execute([DB_NAME]);
    if ($stmt->fetch()) {
        echo "- La columna 'equipo_id' ya existe, se omite.\n";
    } else {
        $db->exec("ALTER TABLE red_nodos ADD COLUMN equipo_id INT NULL AFTER pos_y");
        echo "- Columna 'equipo_id' agregada a red_nodos.\n";
    }

    // La clave foránea se intenta agregar aparte: si ya existe o si hay algún
    // dato inconsistente, no debe frenar la migración (la columna ya alcanza
    // para que la vinculación funcione).
    try {
        $fk = $db->query("
            SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'red_nodos'
              AND COLUMN_NAME = 'equipo_id' AND REFERENCED_TABLE_NAME = 'equipos'
        ")->fetch();
        if (!$fk) {
            $db->exec("ALTER TABLE red_nodos ADD CONSTRAINT fk_red_nodos_equipo FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE SET NULL");
            echo "- Clave foránea equipo_id -> equipos(id) agregada.\n";
        } else {
            echo "- La clave foránea ya existía, se omite.\n";
        }
    } catch (Throwable $e) {
        echo "- No se pudo agregar la clave foránea (no es grave, la vinculación funciona igual): " . $e->getMessage() . "\n";
    }

    echo "\n=== Migración completada. Ya podés borrar este archivo (migrar_vinculo_equipos.php). ===\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
