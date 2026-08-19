<?php
/**
 * Importador de datos reales del colegio
 * ---------------------------------------
 * Este script carga en la base de datos la información que ya existía en:
 *   - "MAC Equipos Aulas --.xlsx"            -> equipos + red (APs y switches)
 *   - "Claves  Equipos - Router 2024.xlsx"   -> contraseñas y accesos
 *   - "SERVIDORES WEB- CLAVES Y VENCIMIENTOS.docx" -> accesos + suscripciones
 *   - Los 3 planos PNG (Planta Baja / Primer Piso / Segundo Piso)
 *
 * CÓMO EJECUTARLO (una sola vez):
 *   1) Verificá que MySQL esté corriendo y que ya importaste database.sql
 *   2) Abrí una terminal (CMD/PowerShell) en esta carpeta y corré:
 *        C:\xampp\php\php.exe importar_datos_reales.php
 *      (o simplemente entrá en el navegador a http://localhost/memoria/importar_datos_reales.php)
 *   3) Cuando termine, BORRÁ este archivo (contiene contraseñas en texto plano
 *      en su propio código fuente). El sistema ya las va a tener guardadas
 *      cifradas en la base de datos.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$db = getDB();
header('Content-Type: text/plain; charset=utf-8');

echo "=== Importador de datos reales - Memoria Técnica Colegio San José ===\n\n";

// =========================================================
// 1) EQUIPOS
// =========================================================
$equiposJson = <<<'JSON_EQUIPOS'
[{"nombre_pc":"PC-2G-2P","mac":"60:83:E7:B7:94:D7","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"3ER AÑO B","sala":"2G","piso":"2P"},{"nombre_pc":"PC-2F-2P","mac":"34-60-F9-1A-0A-2D","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"3ER AÑO A","sala":"2F","piso":"2P"},{"nombre_pc":"PC-2B-2P","mac":"50-3E-AA-9D-DC-26","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"4TO AÑO A","sala":"2B","piso":"2P"},{"nombre_pc":"PC-2A-2P","mac":"50-3E-AA-A2-97-D5","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"SALA INGLES","sala":"2A","piso":"2P"},{"nombre_pc":"PC-VD-2P","mac":"00-D8-61-4F-F7-33","ip":null,"subred":"192.168.4.1","tipo_conexion":"cableada","aula":"MARCELO","sala":"VICEDIRECCION","piso":"2P"},{"nombre_pc":"PC-TT-2P-A","mac":"04-7C-16-15-5C-2B","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"TUTORIA","sala":"TUTORIA","piso":"2P"},{"nombre_pc":"PC-2D-2P","mac":"C0-06-C3-BF-AC-7A","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"2DO AÑO B","sala":"2D","piso":"2P"},{"nombre_pc":"PC-2C-2P","mac":"50-3E-AA-47-8E-AD","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"2DO AÑO A","sala":"2C","piso":"2P"},{"nombre_pc":"PC-2E-2P","mac":"50-3E-AA-9D-C0-63","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"4TO AÑO B","sala":"2E","piso":"2P"},{"nombre_pc":"PC-1H-1P","mac":null,"ip":null,"subred":null,"tipo_conexion":"cableada","aula":"LABORATORIO","sala":null,"piso":"1P"},{"nombre_pc":"PC-1G-1P","mac":"E8-DE-27-9F-BD-A6","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"1ER AÑO B","sala":"1G","piso":"1P"},{"nombre_pc":"PC-1F-1P","mac":"60-83-E7-B7-A3-6C","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"1ER AÑO A","sala":"1F","piso":"1P"},{"nombre_pc":"PC-1E-1P","mac":"60-83-E7-B7-A1-56","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"5TO AÑO A","sala":"1E","piso":"1P"},{"nombre_pc":"PC-1D-1P","mac":"14-CC-20-25-2D-EA","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"5TO AÑO B","sala":"1D","piso":"1P"},{"nombre_pc":"PC-1C-1P","mac":"50-3E-AA-A2-AE-DB","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"6TO AÑO A","sala":"1C","piso":"1P"},{"nombre_pc":"PC-1B-1P","mac":"E8-DE-27-9F-A8-F8","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"6TO AÑO B","sala":"1B","piso":"1P"},{"nombre_pc":"PC-F-PB","mac":"60-83-E7-B7-9F-96","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"6TO GRADO","sala":"F","piso":"PB"},{"nombre_pc":"PC-E-PB","mac":"5C-62-8B-68-20-48","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"5TO GRADO","sala":"E","piso":"PB"},{"nombre_pc":"PC-D-PB","mac":"E8-DE-27-A0-28-CF","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"4TO GRADO","sala":"D","piso":"PB"},{"nombre_pc":"PC-A-PB","mac":"50-3E-AA-A2-97-DA","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"2DO GRADO","sala":"A","piso":"PB"},{"nombre_pc":"PC-L-PB","mac":"C4-E9-84-1C-9C-2B","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"1ER GRADO","sala":"L","piso":"PB"},{"nombre_pc":"PC-K-PB","mac":"5C-62-8B-68-1E-15","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"3ER GRADO","sala":"K","piso":"PB"},{"nombre_pc":"PC-I-PB","mac":"1C-BF-CE-A0-F3-A5","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"SALA DE 4","sala":"I","piso":"PB"},{"nombre_pc":"PC-H-PB","mac":"1C-BF-CE-A0-F3-9D","ip":null,"subred":"192.168.9.1","tipo_conexion":"cableada","aula":"SALA DE 5","sala":"H","piso":"PB"},{"nombre_pc":"NB-SEC-A","mac":"C8-CB-9E-0A-0D-48","ip":null,"subred":null,"tipo_conexion":"cableada","aula":"Notebook Secundario","sala":null,"piso":"1P"},{"nombre_pc":"NB-SEC-B","mac":"4C-ED-DE-CC-30-A5","ip":null,"subred":null,"tipo_conexion":"cableada","aula":"Notebook Secundario","sala":null,"piso":"1P"},{"nombre_pc":"NB-PRI-A","mac":"C8-CB-9E-06-26-C4","ip":null,"subred":null,"tipo_conexion":"cableada","aula":"Notebook Primario","sala":null,"piso":"PB"},{"nombre_pc":"NB-IN-A","mac":"C8-CB-9E-4E-D7","ip":null,"subred":null,"tipo_conexion":"cableada","aula":"Notebook Inicial","sala":null,"piso":"PB"},{"nombre_pc":"TEL-SEC-A","mac":"62-17-6F-48-B7-65","ip":null,"subred":null,"tipo_conexion":"cableada","aula":"Telefono Nivel Secundario","sala":null,"piso":"1P"},{"nombre_pc":"TEL-PRI-A","mac":"92-6A-F5-B2-4D-24","ip":null,"subred":null,"tipo_conexion":"cableada","aula":"Telefono Nivel Primario","sala":null,"piso":"PB"},{"nombre_pc":"PC-DI-PB","mac":null,"ip":null,"subred":null,"tipo_conexion":"cableada","aula":"PC dirección inicial","sala":null,"piso":"PB"}]
JSON_EQUIPOS;

$equipos = json_decode($equiposJson, true);
$stmt = $db->prepare("INSERT INTO equipos (nombre_pc, mac, ip, subred, tipo_conexion, aula, sala, piso) VALUES (?,?,?,?,?,?,?,?)");
$n = 0;
foreach ($equipos as $e) {
    $stmt->execute([$e['nombre_pc'], $e['mac'], $e['ip'], $e['subred'], $e['tipo_conexion'], $e['aula'], $e['sala'], $e['piso']]);
    $n++;
}
echo "Equipos importados: $n\n";

// =========================================================
// 2) RED (access points y switches)
// =========================================================
$redJson = <<<'JSON_RED'
[{"tipo":"access_point","nombre":"AP-L-PB","mac":"F4-92-BF-10-61-51","ip":"192.168.9.12","subred":null,"ssid":null,"ubicacion":"L (Primer grado)","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch ADMINISTRACION, puerto 11"},{"tipo":"access_point","nombre":"AP-1F-1P","mac":"74-83-C2-33-19-9A","ip":"192.168.9.111","subred":null,"ssid":null,"ubicacion":"1F","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch TUTORIA, puerto 24"},{"tipo":"access_point","nombre":"AP-1D-1P","mac":"74-83-C2-33-19-91","ip":"192.168.9.138","subred":null,"ssid":null,"ubicacion":"1D (5TO AÑO)","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch TUTORIA, puerto 22"},{"tipo":"access_point","nombre":"AP-2B-2P","mac":"F0-9F-C2-80-CE-E1","ip":"192.168.9.13","subred":null,"ssid":null,"ubicacion":"2B","piso":null,"marca_modelo":null,"observaciones":null},{"tipo":"access_point","nombre":"AP-AD-PB","mac":"70-A7-41-86-E2-40","ip":"192.168.9.253","subred":null,"ssid":null,"ubicacion":"ADMINISTRACION","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch ADMINISTRACION, puerto 2"},{"tipo":"access_point","nombre":"AP-DO-1P","mac":"74-83-C2-33-13-57","ip":"192.168.9.142","subred":null,"ssid":null,"ubicacion":"GABINETE DOE","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch TUTORIA, puerto 16"},{"tipo":"access_point","nombre":"AP-1H-1P","mac":"74-83-C2-33-3B-02","ip":"192.168.9.254","subred":null,"ssid":null,"ubicacion":"1H","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch BIBLIOTECA, puerto 8"},{"tipo":"access_point","nombre":"AP-PA-2P","mac":"78-8A-20-F3-00-40","ip":"192.168.9.110","subred":null,"ssid":null,"ubicacion":"PASILLO 2P ","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch TUTORIA, puerto 9"},{"tipo":"access_point","nombre":"AP-H-PB","mac":"F4-92-BF-10-61-39","ip":"192.168.9.105","subred":null,"ssid":null,"ubicacion":"H (GABINETE DE CIENCIAS)","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch BIBLIOTECA, puerto 4"},{"tipo":"access_point","nombre":"AP-TT-1P","mac":"74-83-C2-33-39-EE","ip":"192.168.9.14","subred":null,"ssid":null,"ubicacion":"TUTORIAS","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch TUTORIA, puerto 14"},{"tipo":"access_point","nombre":"AP-VD-2P","mac":"74-83-C2-33-19-62","ip":"192.168.9.18","subred":null,"ssid":null,"ubicacion":"VICEDIRECCION","piso":null,"marca_modelo":null,"observaciones":"Conectado a switch TUTORIA, puerto 10"},{"tipo":"switch","nombre":"Biblioteca-Aruba-2530-24G","mac":"3810f0b05360","ip":"192.168.9.10","subred":null,"ssid":null,"ubicacion":"Biblioteca","piso":"PB","marca_modelo":"2530-24G","observaciones":"Puerto 1 -> Router Biblioteca"},{"tipo":"switch","nombre":"Switch-Administracion","mac":"B8:D4:E7:EA:98:E9","ip":"192.168.9.15","subred":null,"ssid":null,"ubicacion":"Administracion","piso":"PB","marca_modelo":"1930-24G","observaciones":"Puerto 21 -> Switch Tutoria"},{"tipo":"switch","nombre":"Aruba-2530-24G-Tutoria-1P","mac":"3810f0b04340","ip":"192.168.9.3","subred":null,"ssid":null,"ubicacion":"Tutoria","piso":"1P","marca_modelo":"2530-24G","observaciones":"Puerto 15 -> Switch Biblioteca; Puerto 23 -> Router Administracion"}]
JSON_RED;

$red = json_decode($redJson, true);
$stmt = $db->prepare("INSERT INTO red_dispositivos (tipo, nombre, mac, ip, subred, ssid, ubicacion, piso, marca_modelo, observaciones) VALUES (?,?,?,?,?,?,?,?,?,?)");
$n = 0;
foreach ($red as $r) {
    $stmt->execute([$r['tipo'], $r['nombre'], $r['mac'], $r['ip'], $r['subred'], $r['ssid'], $r['ubicacion'], $r['piso'], $r['marca_modelo'], $r['observaciones']]);
    $n++;
}
echo "Dispositivos de red importados: $n\n";

// =========================================================
// 3) ACCESOS (contraseñas)
// =========================================================
$accesosJson = <<<'JSON_ACCESOS'
[{"servicio":"PC Biblioteca","usuario":"admin","password":"rma753","url":null,"observaciones":"Ubicación: Biblioteca PB | Red: Biblioteca | la contraseña es igual, pero no funciona mas como servidor | IP: 192.168.9.28","fecha_actualizacion":"2024-11-06"},{"servicio":"Consola Unify","usuario":"admin","password":"rma753rma","url":null,"observaciones":"Ubicación: Biblioteca PB | Red: Biblioteca | La contraseña es la misma pero el servidor se encuentra ahora en tutorias y tiene otra configuracion | IP: 192.168.9.28","fecha_actualizacion":"2024-11-06"},{"servicio":"Servidor Linux","usuario":"ngadmin","password":"mra1245","url":null,"observaciones":"Ubicación: Gabinete PB | Red: Administracion | Obsoleta, el servidor fue retirado de servicio","fecha_actualizacion":"2024-11-06"},{"servicio":"Servidor Linux","usuario":"admin","password":"adm1245","url":null,"observaciones":"Ubicación: Gabinete SS | Red: Administracion","fecha_actualizacion":"2024-11-06"},{"servicio":"Routers","usuario":"colegio","password":"Cole-1245Adm","url":null,"observaciones":"Ubicación: General","fecha_actualizacion":"2024-11-06"},{"servicio":"Switchs Aruba","usuario":"colegio","password":"Cole-1245Adm","url":null,"observaciones":"Ubicación: General","fecha_actualizacion":"2024-11-06"},{"servicio":"Modem","usuario":"custadmin","password":"f4st3890","url":null,"observaciones":"Ubicación: Biblioteca PB | Red: Biblioteca","fecha_actualizacion":"2024-11-06"},{"servicio":"PCs Aulas PB 1P","usuario":"admin","password":"adm1245","url":null,"observaciones":"Red: Administracion | Contraseña anterior: admin1245","fecha_actualizacion":"2024-11-06"},{"servicio":"PCs Aulas 2P","usuario":"admin","password":"Admin2134","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"Cloud","usuario":"csjose","password":"Adm1245CSJ","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"Router Biblioteca Mikrotik","usuario":"admin","password":"admin213471118","url":null,"observaciones":"Ubicación: Biblioteca PB | Red: Biblioteca","fecha_actualizacion":"2024-11-06"},{"servicio":"Unifi Console SSH access","usuario":"lucasgonzalez","password":"2U56mdbEOBYJK7mM","url":null,"observaciones":"Red: Biblioteca | IP: 192.168.251.0 (reconstruida, verificar) | Contraseña anterior: QQbQXglr5JVAyU3UyR8Ak7xG","fecha_actualizacion":"2024-11-06"},{"servicio":"servidor Unifi","usuario":"administrador","password":"rma753","url":null,"observaciones":"Ubicación: Tutorías Entrepiso | Red: Biblioteca | Host: csj-network-server | es el nuevo servidor al dia 26 de AGOSTO","fecha_actualizacion":"2024-11-06"},{"servicio":"PC Tercer Año A","usuario":"Tercero","password":"tercero","url":null,"observaciones":"Ubicación: Segundo Piso | Red: Biblioteca | recuperacion tercero","fecha_actualizacion":"2024-11-06"},{"servicio":"PC Tercer Año A","usuario":"Admin","password":"Admin1245","url":null,"observaciones":"Ubicación: Segundo Piso | Red: Biblioteca | recuperacion admin","fecha_actualizacion":"2024-11-06"},{"servicio":"PC Tercer Año A","usuario":"Dirección","password":"direccion5813","url":null,"observaciones":"Ubicación: Segundo Piso | Red: Biblioteca | recuperacion direccionF","fecha_actualizacion":"2024-11-06"},{"servicio":"Veyon PC Docente","usuario":"Profesor","password":"Profesor1245","url":null,"observaciones":"Ubicación: Gabinete PB | Red: Administracion","fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente","usuario":"admin","password":"adm1245","url":null,"observaciones":"Ubicación: Gabinete Ciencias | Red: PP AP-1H-1P","fecha_actualizacion":"2024-11-06"},{"servicio":"Pc Dirección Primaria","usuario":"sin especificar","password":"adm1245","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC 1-15 (GPB)","usuario":"Admin","password":"Admin1245","url":null,"observaciones":"Contraseña para las maquinas 1-15 | Contraseña anterior: admin1245","fecha_actualizacion":"2024-11-06"},{"servicio":"PC 1-15 (GPB)","usuario":"Admin","password":"Admin467873","url":null,"observaciones":"Contraseña nueva, implementada en la PC09 | Respuesta recuperación: colegiodesanjosegpb | Contraseña anterior: Admin1245","fecha_actualizacion":"2024-11-06"},{"servicio":"PC 11-15 (GPB)","usuario":"4°B","password":"4b084533","url":null,"observaciones":"Respuesta recuperación: secundariocuartob","fecha_actualizacion":"2024-11-06"},{"servicio":"PC 11-15 (GPB)","usuario":"2°B","password":"2b518502","url":null,"observaciones":"Respuesta recuperación: secundariosegundob","fecha_actualizacion":"2024-11-06"},{"servicio":"PC 11-15 (GPB)","usuario":"5°B","password":"5b519652","url":null,"observaciones":"Respuesta recuperación: secundarioquintob","fecha_actualizacion":"2024-11-06"},{"servicio":"PC 11-15 (GPB)","usuario":"6°B","password":"6b149159","url":null,"observaciones":"Respuesta recuperación: secundariosextob","fecha_actualizacion":"2024-11-06"},{"servicio":"PC 11-15 (GPB)","usuario":"Colegio","password":"cole746145","url":null,"observaciones":"Usuario para uso general por cursos invitados | Respuesta recuperación: colegiodesanjosecole","fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Tecnico","password":"Admin2134","url":null,"observaciones":"Contraseña anterior: Admin1245","fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Profe Mayra","password":"Sanjo1984","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Alumno","password":"no tiene","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Profe Gastòn","password":"13231414","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Profe Marit","password":"Sanjo1984","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Profe Pablo","password":"Sanjo1984","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Docente (GPB)","usuario":"Profe Alejandra","password":"Sanjo1984","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Gabinete Subsuelo","usuario":"Admin","password":"sanjo2022","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Gabinete Subsuelo","usuario":"Primario","password":"prima742","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Gabinete Subsuelo","usuario":"Secundario","password":"secun956","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Gabinete Subsuelo","usuario":"PRIMARIO","password":"prima2023","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Gabinete Subsuelo","usuario":"secundario","password":"secun2023","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"PC Gabinete Subsuelo","usuario":"administrador","password":"docente2025","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"Notebook Tutorias (Tutorias Secundario 2P)","usuario":"Tutoria","password":"Tuto9355","url":null,"observaciones":"Respuesta recuperación: Recup tuto","fecha_actualizacion":"2024-11-06"},{"servicio":"Notebook Tutorias (Tutorias Secundario 2P)","usuario":"Admin","password":"Admin1245","url":null,"observaciones":"Confirmar contraseña","fecha_actualizacion":"2024-11-06"},{"servicio":"Notebook Primaria (Direccion Primaria PB)","usuario":"Admin","password":"Admin1245","url":null,"observaciones":"Confirmar contraseña","fecha_actualizacion":"2024-11-06"},{"servicio":"Notebook Primaria (Direccion Primaria PB)","usuario":"Docentes","password":"doce1314","url":null,"observaciones":null,"fecha_actualizacion":"2024-11-06"},{"servicio":"Notebook Secundaria (Direccion Primaria PB)","usuario":"Docentes","password":"doce1314","url":null,"observaciones":null,"fecha_actualizacion":"2026-04-06"},{"servicio":"NUTHOST - Campus Virtual (Moodle)","usuario":"administracion@colegiodesanjose.edu.ar","password":"MONO2139","url":"https://campuscolegiodesanjose.com","observaciones":"Dominio vence 28/01/2027. Servidor vence 22/02/2027.","fecha_actualizacion":"2026-08-18"},{"servicio":"GoDaddy - Sitio web del colegio","usuario":"administracion@colegiodesanjose.edu.ar","password":"AdminSanjo2012","url":"https://godaddy.com","observaciones":"También es la contraseña usada para backup. Cliente 144796833, PIN 9613. Vence 20/02/2028.","fecha_actualizacion":"2026-08-18"},{"servicio":"Hostinger - Biblioteca y sitios de estudiantes","usuario":"informaticasecundario@colegiodesanjose.edu.ar","password":"$$159Info%%","url":"https://hostinger.com","observaciones":"Vence 18/08/2026.","fecha_actualizacion":"2026-08-18"}]
JSON_ACCESOS;

// Categorías nuevas para clasificar los accesos reales
$categoriasNuevas = ['Servidores y Redes', 'PCs y Notebooks', 'Sitios Web y Hosting'];
foreach ($categoriasNuevas as $cat) {
    $db->prepare("INSERT IGNORE INTO categorias_accesos (nombre) VALUES (?)")->execute([$cat]);
}
$catIds = [];
foreach ($db->query("SELECT id, nombre FROM categorias_accesos") as $row) {
    $catIds[$row['nombre']] = $row['id'];
}

function categorizarAcceso(string $servicio, ?string $obs): ?int
{
    global $catIds;
    $s = mb_strtolower($servicio . ' ' . ($obs ?? ''));
    $redKeys = ['router', 'switch', 'modem', 'unifi', 'servidor', 'ap-', 'mikrotik'];
    $pcKeys = ['pc ', 'pc(', 'notebook', 'nb-', 'tel-', 'docente', 'gabinete', 'veyon', 'aula'];
    $webKeys = ['nuthost', 'godaddy', 'hostinger', 'cloud', 'moodle', 'campus'];
    foreach ($webKeys as $k) if (str_contains($s, $k)) return $catIds['Sitios Web y Hosting'] ?? null;
    foreach ($redKeys as $k) if (str_contains($s, $k)) return $catIds['Servidores y Redes'] ?? null;
    foreach ($pcKeys as $k) if (str_contains($s, $k)) return $catIds['PCs y Notebooks'] ?? null;
    return $catIds['Otros'] ?? null;
}

$accesos = json_decode($accesosJson, true);
$stmt = $db->prepare("INSERT INTO accesos (servicio, usuario, password_cifrada, iv, url, categoria_id, observaciones, fecha_actualizacion) VALUES (?,?,?,?,?,?,?,?)");
$n = 0;
foreach ($accesos as $a) {
    $enc = encryptString($a['password']);
    $catId = categorizarAcceso($a['servicio'], $a['observaciones']);
    $stmt->execute([$a['servicio'], $a['usuario'], $enc['data'], $enc['iv'], $a['url'], $catId, $a['observaciones'], $a['fecha_actualizacion']]);
    $n++;
}
echo "Accesos/contraseñas importados: $n\n";

// =========================================================
// 4) SUSCRIPCIONES (hosting, dominio, servidor)
// =========================================================
$suscripcionesJson = <<<'JSON_SUSC'
[{"proveedor":"NUTHOST","servicio":"Dominio campuscolegiodesanjose.com (Moodle)","fecha_vencimiento":"2027-01-28","periodo_renovacion":"anual","observaciones":"Usuario: administracion@colegiodesanjose.edu.ar"},{"proveedor":"NUTHOST","servicio":"Servidor Campus Virtual (Moodle)","fecha_vencimiento":"2027-02-22","periodo_renovacion":"anual","observaciones":"Usuario: administracion@colegiodesanjose.edu.ar"},{"proveedor":"GODADDY","servicio":"Sitio web del colegio","fecha_vencimiento":"2028-02-20","periodo_renovacion":"anual","observaciones":"Cliente 144796833, PIN 9613."},{"proveedor":"HOSTINGER","servicio":"Biblioteca y sitios de estudiantes","fecha_vencimiento":"2026-08-18","periodo_renovacion":"anual","observaciones":"Usuario: informaticasecundario@colegiodesanjose.edu.ar"}]
JSON_SUSC;

$suscripciones = json_decode($suscripcionesJson, true);
$stmt = $db->prepare("INSERT INTO suscripciones (proveedor, servicio, fecha_vencimiento, periodo_renovacion, observaciones) VALUES (?,?,?,?,?)");
$n = 0;
foreach ($suscripciones as $s) {
    $stmt->execute([$s['proveedor'], $s['servicio'], $s['fecha_vencimiento'], $s['periodo_renovacion'], $s['observaciones']]);
    $n++;
}
echo "Suscripciones importadas: $n\n";

// =========================================================
// 5) DOCUMENTOS (planos ya copiados a uploads/documentos/)
// =========================================================
$catPlanos = $db->query("SELECT id FROM categorias_documentos WHERE nombre = 'Planos del colegio'")->fetchColumn();
$planos = [
    ['nombre' => 'Plano Planta Baja', 'archivo' => 'plano_planta_baja.png', 'original' => 'CSJ Planta Baja con los colores.png'],
    ['nombre' => 'Plano Primer Piso', 'archivo' => 'plano_primer_piso.png', 'original' => 'CSJ Primer Piso con los colores.png'],
    ['nombre' => 'Plano Segundo Piso', 'archivo' => 'plano_segundo_piso.png', 'original' => 'CSJ Segundo Piso con los colores.png'],
];
$stmt = $db->prepare("INSERT INTO documentos (categoria_id, nombre, descripcion, archivo_path, archivo_original, tipo_archivo) VALUES (?,?,?,?,?,?)");
$n = 0;
foreach ($planos as $p) {
    $full = UPLOADS_DIR . '/' . $p['archivo'];
    if (is_file($full)) {
        $stmt->execute([$catPlanos ?: null, $p['nombre'], 'Plano con distribución de aulas por colores.', $p['archivo'], $p['original'], 'png']);
        $n++;
    } else {
        echo "  (!) No se encontró el archivo {$p['archivo']} en uploads/documentos/, se omitió su registro.\n";
    }
}
echo "Documentos (planos) registrados: $n\n";

echo "\n=== Importación completada ===\n";
echo "Ahora podés borrar este archivo (importar_datos_reales.php) por seguridad.\n";
