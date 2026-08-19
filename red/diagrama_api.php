<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

header('Content-Type: application/json; charset=utf-8');

function jsonOut($data, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

$db = getDB();
$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

// Todas las acciones de escritura requieren poder editar (admin/técnico)
$accionesEscritura = ['crear_nodo', 'editar_nodo', 'mover_nodo', 'eliminar_nodo', 'crear_conexion', 'eliminar_conexion'];
if (in_array($accion, $accionesEscritura, true) && !canEdit()) {
    jsonOut(['ok' => false, 'error' => 'No tenés permisos para modificar el diagrama.'], 403);
}

try {
    switch ($accion) {

        case 'listar': {
            $nodos = $db->query('SELECT * FROM red_nodos ORDER BY id')->fetchAll();
            $conexiones = $db->query('SELECT * FROM red_conexiones ORDER BY id')->fetchAll();
            jsonOut(['ok' => true, 'nodos' => $nodos, 'conexiones' => $conexiones]);
        }

        case 'crear_nodo': {
            $tipo = $_POST['tipo'] ?? 'otro';
            $nombre = trim($_POST['nombre'] ?? '');
            if ($nombre === '') jsonOut(['ok' => false, 'error' => 'El nombre es obligatorio.'], 422);
            $numPuertos = max(1, (int)($_POST['num_puertos'] ?? 1));
            $stmt = $db->prepare("INSERT INTO red_nodos (tipo, nombre, subtitulo, ip, grupo, info_extra, num_puertos, pos_x, pos_y) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([
                $tipo, $nombre,
                trim($_POST['subtitulo'] ?? '') ?: null,
                trim($_POST['ip'] ?? '') ?: null,
                trim($_POST['grupo'] ?? '') ?: null,
                trim($_POST['info_extra'] ?? '') ?: null,
                $numPuertos,
                (int)($_POST['pos_x'] ?? 100),
                (int)($_POST['pos_y'] ?? 100),
            ]);
            $id = (int)$db->lastInsertId();
            $nodo = $db->query('SELECT * FROM red_nodos WHERE id = ' . $id)->fetch();
            jsonOut(['ok' => true, 'nodo' => $nodo]);
        }

        case 'editar_nodo': {
            $id = (int)($_POST['id'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            if (!$id || $nombre === '') jsonOut(['ok' => false, 'error' => 'Datos incompletos.'], 422);
            $numPuertos = max(1, (int)($_POST['num_puertos'] ?? 1));
            $stmt = $db->prepare("UPDATE red_nodos SET tipo=?, nombre=?, subtitulo=?, ip=?, grupo=?, info_extra=?, num_puertos=? WHERE id=?");
            $stmt->execute([
                $_POST['tipo'] ?? 'otro', $nombre,
                trim($_POST['subtitulo'] ?? '') ?: null,
                trim($_POST['ip'] ?? '') ?: null,
                trim($_POST['grupo'] ?? '') ?: null,
                trim($_POST['info_extra'] ?? '') ?: null,
                $numPuertos, $id,
            ]);
            jsonOut(['ok' => true]);
        }

        case 'mover_nodo': {
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) jsonOut(['ok' => false, 'error' => 'Falta id.'], 422);
            $stmt = $db->prepare("UPDATE red_nodos SET pos_x=?, pos_y=? WHERE id=?");
            $stmt->execute([(int)($_POST['pos_x'] ?? 0), (int)($_POST['pos_y'] ?? 0), $id]);
            jsonOut(['ok' => true]);
        }

        case 'eliminar_nodo': {
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) jsonOut(['ok' => false, 'error' => 'Falta id.'], 422);
            $db->prepare("DELETE FROM red_nodos WHERE id = ?")->execute([$id]);
            jsonOut(['ok' => true]);
        }

        case 'crear_conexion': {
            $origen = (int)($_POST['nodo_origen_id'] ?? 0);
            $destino = (int)($_POST['nodo_destino_id'] ?? 0);
            if (!$origen || !$destino) jsonOut(['ok' => false, 'error' => 'Faltan nodos.'], 422);
            $stmt = $db->prepare("INSERT INTO red_conexiones (nodo_origen_id, puerto_origen, nodo_destino_id, puerto_destino, etiqueta) VALUES (?,?,?,?,?)");
            $stmt->execute([
                $origen, max(1, (int)($_POST['puerto_origen'] ?? 1)),
                $destino, max(1, (int)($_POST['puerto_destino'] ?? 1)),
                trim($_POST['etiqueta'] ?? '') ?: null,
            ]);
            $id = (int)$db->lastInsertId();
            jsonOut(['ok' => true, 'id' => $id]);
        }

        case 'eliminar_conexion': {
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) jsonOut(['ok' => false, 'error' => 'Falta id.'], 422);
            $db->prepare("DELETE FROM red_conexiones WHERE id = ?")->execute([$id]);
            jsonOut(['ok' => true]);
        }

        default:
            jsonOut(['ok' => false, 'error' => 'Acción no reconocida.'], 400);
    }
} catch (Throwable $e) {
    jsonOut(['ok' => false, 'error' => $e->getMessage()], 500);
}
