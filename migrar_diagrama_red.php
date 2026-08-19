<?php
/**
 * Migración única: crea las tablas del diagrama de red interactivo
 * (red_nodos, red_conexiones) y las precarga con los dispositivos que
 * figuran en el plano de red PDF que subió el colegio.
 *
 * IMPORTANTE: la posición de cada equipo en el mapa y el "backbone"
 * (conexión entre switches/router/módems) es una primera reconstrucción
 * automática a partir del plano. Los datos de cada dispositivo (nombre,
 * IP, grupo, impresora, número de puerto de referencia) son fieles al
 * plano, pero algunas líneas de conexión entre equipos finales y su
 * switch NO se dibujaron para no arriesgarse a adivinar mal el puerto
 * exacto: quedaron con el número de puerto anotado en "Información
 * adicional" para que las conectes vos mismo arrastrando desde el
 * diagrama (tarda un par de minutos y así el diagrama queda validado).
 *
 * Ejecutar UNA sola vez desde el navegador:
 *   http://localhost/memoria/migrar_diagrama_red.php
 * y luego borrar este archivo.
 */
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/plain; charset=utf-8');
echo "=== Migración: diagrama de red interactivo ===\n\n";

try {
    $db = getDB();

    $db->exec("CREATE TABLE IF NOT EXISTS red_nodos (
      id INT AUTO_INCREMENT PRIMARY KEY,
      tipo ENUM('pc','switch','router','modem','ap','impresora','camara','servidor','lector','conector','otro') NOT NULL DEFAULT 'otro',
      nombre VARCHAR(150) NOT NULL,
      subtitulo VARCHAR(150) NULL,
      ip VARCHAR(50) NULL,
      grupo VARCHAR(100) NULL,
      info_extra TEXT NULL,
      num_puertos INT NOT NULL DEFAULT 1,
      pos_x INT NOT NULL DEFAULT 100,
      pos_y INT NOT NULL DEFAULT 100,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
    echo "- Tabla 'red_nodos' lista.\n";

    $db->exec("CREATE TABLE IF NOT EXISTS red_conexiones (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nodo_origen_id INT NOT NULL,
      puerto_origen INT NOT NULL DEFAULT 1,
      nodo_destino_id INT NOT NULL,
      puerto_destino INT NOT NULL DEFAULT 1,
      etiqueta VARCHAR(100) NULL,
      created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (nodo_origen_id) REFERENCES red_nodos(id) ON DELETE CASCADE,
      FOREIGN KEY (nodo_destino_id) REFERENCES red_nodos(id) ON DELETE CASCADE
    ) ENGINE=InnoDB");
    echo "- Tabla 'red_conexiones' lista.\n\n";

    $yaHayDatos = (int)$db->query('SELECT COUNT(*) c FROM red_nodos')->fetch()['c'];
    if ($yaHayDatos > 0) {
        echo "Ya hay $yaHayDatos dispositivo(s) cargados en el diagrama, no se vuelve a precargar.\n";
        echo "\n=== Migración completada. Ya podés borrar este archivo (migrar_diagrama_red.php). ===\n";
        exit;
    }

    $nodosJson = <<<'JSON_NODOS'
[
  {"key":"internet_bib","tipo":"otro","nombre":"Internet","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Enlace de internet - Biblioteca","num_puertos":1,"pos_x":40,"pos_y":40},
  {"key":"modem_bib","tipo":"modem","nombre":"Módem Internet Biblioteca","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Ubicación: ETH1","num_puertos":2,"pos_x":40,"pos_y":190},
  {"key":"switch_bib","tipo":"switch","nombre":"Switch Biblioteca","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Of Carlos, puerto 17","num_puertos":24,"pos_x":300,"pos_y":190},
  {"key":"internet_carlos","tipo":"otro","nombre":"Internet","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Enlace de internet - Oficina Carlos","num_puertos":1,"pos_x":40,"pos_y":360},
  {"key":"router_board","tipo":"router","nombre":"Router Board 1100","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Ubicación: ETH4. Conecta a Switch Of Carlos, puerto 24","num_puertos":5,"pos_x":40,"pos_y":510},
  {"key":"modem_carlos","tipo":"modem","nombre":"Módem Internet Carlos","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Of Carlos, puerto 16","num_puertos":2,"pos_x":300,"pos_y":510},
  {"key":"switch_carlos","tipo":"switch","nombre":"Switch Of Carlos","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Switch troncal / central del colegio","num_puertos":24,"pos_x":600,"pos_y":360},
  {"key":"switch_tutoria","tipo":"switch","nombre":"Switch Tutoria","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto propio: 3. Conecta a Switch Of Carlos, puerto 21","num_puertos":24,"pos_x":960,"pos_y":150},
  {"key":"switch_secretaria_sec","tipo":"switch","nombre":"Switch Secretaria Secundario","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Tutoria","num_puertos":8,"pos_x":1300,"pos_y":20},
  {"key":"switch_inf_pb","tipo":"switch","nombre":"Switch Informática PB","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Of Carlos, puerto 20","num_puertos":16,"pos_x":960,"pos_y":510},
  {"key":"switch_inf_ss","tipo":"switch","nombre":"Switch Informática (Sub Suelo)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Of Carlos, puerto 15","num_puertos":16,"pos_x":600,"pos_y":680},

  {"key":"servidor_oficina_carlos","tipo":"servidor","nombre":"Servidor Oficina Carlos","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Of Carlos, puerto 5","num_puertos":4,"pos_x":40,"pos_y":840},
  {"key":"servidor_tecnica","tipo":"servidor","nombre":"Servidor Técnica","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conecta a Switch Tutoria, puerto 13","num_puertos":4,"pos_x":1500,"pos_y":510},

  {"key":"pc_sergio","tipo":"pc","nombre":"PC - Sergio","subtitulo":"DESKTOP-H9BV0F2","ip":"192.168.4.92","grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Tutoria: 5","num_puertos":1,"pos_x":1500,"pos_y":1280},
  {"key":"pc_mariel","tipo":"pc","nombre":"PC - Mariel","subtitulo":"VICEDIRECCION-NS","ip":"192.168.4.107","grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Tutoria: 8","num_puertos":1,"pos_x":1500,"pos_y":1130},
  {"key":"pc_maru","tipo":"pc","nombre":"PC - Maru","subtitulo":"adm-secm","ip":"192.168.4.223","grupo":"ADM-SANJOSE","info_extra":"Puerto de referencia en Switch Tutoria: 7","num_puertos":1,"pos_x":1500,"pos_y":1430},
  {"key":"pc_gaby","tipo":"pc","nombre":"PC - Gaby","subtitulo":"Secretaria-np","ip":"192.168.4.155","grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Of Carlos: 9","num_puertos":1,"pos_x":1340,"pos_y":840},
  {"key":"pc_carlos","tipo":"pc","nombre":"PC - Carlos","subtitulo":"secAdmin","ip":"192.168.4.30","grupo":"RED","info_extra":"Puerto de referencia en Switch Of Carlos: 1","num_puertos":1,"pos_x":700,"pos_y":840},
  {"key":"pc_andres","tipo":"pc","nombre":"PC - Andres","subtitulo":"DESKTOP-NM0V3PT","ip":"192.168.4.4","grupo":null,"info_extra":"Puerto de referencia en Switch Of Carlos: 3","num_puertos":1,"pos_x":910,"pos_y":840},
  {"key":"pc_liliana","tipo":"pc","nombre":"PC - Liliana","subtitulo":"DESKTOP-9123RD8","ip":"192.168.4.82","grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Of Carlos: 7","num_puertos":1,"pos_x":1120,"pos_y":840},
  {"key":"pc_adriana","tipo":"pc","nombre":"PC - Adriana","subtitulo":"PC-DI-PB","ip":"192.168.4.126","grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Of Carlos: 13","num_puertos":1,"pos_x":40,"pos_y":1160},
  {"key":"pc_anto","tipo":"pc","nombre":"PC - Anto","subtitulo":"admin-tuto","ip":"192.168.9.60","grupo":"WORKGROUP","info_extra":null,"num_puertos":1,"pos_x":1720,"pos_y":190},
  {"key":"pc_psicologa","tipo":"pc","nombre":"PC - Psicóloga","subtitulo":"DESKTOP-H2GDQ0O","ip":"192.168.9.169","grupo":"WORKGROUP","info_extra":null,"num_puertos":1,"pos_x":1500,"pos_y":190},

  {"key":"pc_sala_profe_primaria","tipo":"pc","nombre":"PC - Sala Profe Primaria","subtitulo":null,"ip":null,"grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Of Carlos: 19","num_puertos":1,"pos_x":490,"pos_y":840},
  {"key":"pc_porteria","tipo":"pc","nombre":"PC - Portería","subtitulo":null,"ip":null,"grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Biblioteca: 1","num_puertos":1,"pos_x":40,"pos_y":790},
  {"key":"pc_biblioteca","tipo":"pc","nombre":"PC - Biblioteca","subtitulo":null,"ip":null,"grupo":"WORKGROUP","info_extra":"Puerto de referencia en Switch Biblioteca: 3","num_puertos":1,"pos_x":460,"pos_y":790},
  {"key":"pc_sexto_grado","tipo":"pc","nombre":"PC - Sexto Grado","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 16","num_puertos":1,"pos_x":40,"pos_y":960},
  {"key":"pc_tutoria_dany","tipo":"pc","nombre":"PC - Tutoría (Dany)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 16","num_puertos":1,"pos_x":1500,"pos_y":980},
  {"key":"pc_sala_profe_sec","tipo":"pc","nombre":"PC - Sala Profe Secundaria","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 18","num_puertos":1,"pos_x":1500,"pos_y":830},
  {"key":"pc_tecnica_gaston","tipo":"pc","nombre":"PC - Técnica (Gastón)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 15","num_puertos":1,"pos_x":1500,"pos_y":680},

  {"key":"impresora_sergio","tipo":"impresora","nombre":"Impresora HP M203dw (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Sergio","num_puertos":1,"pos_x":1720,"pos_y":1280},
  {"key":"impresora_mariel","tipo":"impresora","nombre":"Impresora HP M102W (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Mariel","num_puertos":1,"pos_x":1720,"pos_y":1130},
  {"key":"impresora_maru","tipo":"impresora","nombre":"Impresora M236sdw (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Maru","num_puertos":1,"pos_x":1720,"pos_y":1430},
  {"key":"impresora_gaby","tipo":"impresora","nombre":"Impresora CP1025 nw (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Gaby","num_puertos":1,"pos_x":1340,"pos_y":990},
  {"key":"impresora_carlos","tipo":"impresora","nombre":"Impresora HP M212 (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Carlos","num_puertos":1,"pos_x":700,"pos_y":990},
  {"key":"impresora_andres","tipo":"impresora","nombre":"Impresora HP M203dw (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Andres","num_puertos":1,"pos_x":910,"pos_y":990},
  {"key":"impresora_liliana","tipo":"impresora","nombre":"Impresora M426FDW (Red)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conectada por red (no USB) - PC Liliana","num_puertos":1,"pos_x":1120,"pos_y":990},
  {"key":"impresora_adriana","tipo":"impresora","nombre":"Impresora HP P1005 (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Adriana","num_puertos":1,"pos_x":250,"pos_y":1160},
  {"key":"impresora_anto","tipo":"impresora","nombre":"Impresora HP M236sdw (red)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Conectada por red (no USB) - PC Anto","num_puertos":1,"pos_x":1930,"pos_y":190},
  {"key":"impresora_psicologa","tipo":"impresora","nombre":"Impresora HP M111w (USB)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"USB en PC - Psicóloga","num_puertos":1,"pos_x":1500,"pos_y":340},
  {"key":"impresora_tutoria","tipo":"impresora","nombre":"Impresora Tutoría (M236sdw)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 11","num_puertos":1,"pos_x":1500,"pos_y":1580},
  {"key":"impresora_secretaria_sec","tipo":"impresora","nombre":"Impresora M236sdw - Secretaria Secundaria","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 19","num_puertos":1,"pos_x":1500,"pos_y":1730},

  {"key":"ap_ad_pb","tipo":"ap","nombre":"AP-AD-PB","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 2","num_puertos":1,"pos_x":700,"pos_y":790},
  {"key":"ap_l_pb","tipo":"ap","nombre":"AP-L-PB","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 11","num_puertos":1,"pos_x":250,"pos_y":790},
  {"key":"ap_h_pb","tipo":"ap","nombre":"AP-H-PB","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 4","num_puertos":1,"pos_x":910,"pos_y":790},
  {"key":"ap_sala_cuatro","tipo":"ap","nombre":"AP-Sala de Cuatro","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 18","num_puertos":1,"pos_x":250,"pos_y":960},
  {"key":"ap_biblioteca","tipo":"ap","nombre":"AP-BIBLIOTECA","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 23","num_puertos":1,"pos_x":460,"pos_y":960},
  {"key":"ap_2b_2p","tipo":"ap","nombre":"AP-2B-2P","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 6","num_puertos":1,"pos_x":960,"pos_y":20},
  {"key":"ap_pa_2p","tipo":"ap","nombre":"AP-PA-2P","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 12","num_puertos":1,"pos_x":1170,"pos_y":20},
  {"key":"ap_tt_1p","tipo":"ap","nombre":"AP-TT-1P","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 14","num_puertos":1,"pos_x":2140,"pos_y":20},
  {"key":"ap_do_1p","tipo":"ap","nombre":"AP-DO-1P","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 22","num_puertos":1,"pos_x":2350,"pos_y":20},
  {"key":"ap_1d_1p","tipo":"ap","nombre":"AP-1D-1P","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia en Switch Tutoria: 24","num_puertos":1,"pos_x":2560,"pos_y":20},

  {"key":"plato_nuevo","tipo":"otro","nombre":"Plato / molinete (control de acceso)","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 2","num_puertos":1,"pos_x":250,"pos_y":190},
  {"key":"conector_salon_actos","tipo":"conector","nombre":"Conector - Salón de Actos","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 12","num_puertos":1,"pos_x":1120,"pos_y":790},
  {"key":"conector_sala_profe","tipo":"conector","nombre":"Conector - Sala de Profesores","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 10","num_puertos":1,"pos_x":280,"pos_y":840},
  {"key":"camara_biblioteca","tipo":"camara","nombre":"Cámara Biblioteca","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 20","num_puertos":1,"pos_x":670,"pos_y":960},
  {"key":"camara_ihua","tipo":"camara","nombre":"Cámara @lhua","subtitulo":null,"ip":null,"grupo":null,"info_extra":null,"num_puertos":1,"pos_x":460,"pos_y":1160},
  {"key":"lector_biometrico_pb","tipo":"lector","nombre":"Lector biométrico Planta Baja","subtitulo":null,"ip":null,"grupo":null,"info_extra":"Puerto de referencia: 18","num_puertos":1,"pos_x":250,"pos_y":1300}
]
JSON_NODOS;

    $conexionesJson = <<<'JSON_CONEXIONES'
[
  {"origen":"internet_bib","puerto_origen":1,"destino":"modem_bib","puerto_destino":1},
  {"origen":"modem_bib","puerto_origen":2,"destino":"switch_bib","puerto_destino":1},
  {"origen":"switch_bib","puerto_origen":17,"destino":"switch_carlos","puerto_destino":17},
  {"origen":"internet_carlos","puerto_origen":1,"destino":"router_board","puerto_destino":1},
  {"origen":"router_board","puerto_origen":4,"destino":"switch_carlos","puerto_destino":24},
  {"origen":"modem_carlos","puerto_origen":2,"destino":"switch_carlos","puerto_destino":16},
  {"origen":"switch_tutoria","puerto_origen":21,"destino":"switch_carlos","puerto_destino":21},
  {"origen":"switch_secretaria_sec","puerto_origen":1,"destino":"switch_tutoria","puerto_destino":9},
  {"origen":"switch_inf_pb","puerto_origen":1,"destino":"switch_carlos","puerto_destino":20},
  {"origen":"switch_inf_ss","puerto_origen":1,"destino":"switch_carlos","puerto_destino":15},
  {"origen":"servidor_oficina_carlos","puerto_origen":1,"destino":"switch_carlos","puerto_destino":5},
  {"origen":"servidor_tecnica","puerto_origen":1,"destino":"switch_tutoria","puerto_destino":13},

  {"origen":"impresora_sergio","puerto_origen":1,"destino":"pc_sergio","puerto_destino":1},
  {"origen":"impresora_mariel","puerto_origen":1,"destino":"pc_mariel","puerto_destino":1},
  {"origen":"impresora_maru","puerto_origen":1,"destino":"pc_maru","puerto_destino":1},
  {"origen":"impresora_gaby","puerto_origen":1,"destino":"pc_gaby","puerto_destino":1},
  {"origen":"impresora_carlos","puerto_origen":1,"destino":"pc_carlos","puerto_destino":1},
  {"origen":"impresora_andres","puerto_origen":1,"destino":"pc_andres","puerto_destino":1},
  {"origen":"impresora_adriana","puerto_origen":1,"destino":"pc_adriana","puerto_destino":1},
  {"origen":"impresora_psicologa","puerto_origen":1,"destino":"pc_psicologa","puerto_destino":1}
]
JSON_CONEXIONES;

    $nodos = json_decode($nodosJson, true);
    $conexiones = json_decode($conexionesJson, true);

    $stmtNodo = $db->prepare("INSERT INTO red_nodos (tipo, nombre, subtitulo, ip, grupo, info_extra, num_puertos, pos_x, pos_y) VALUES (?,?,?,?,?,?,?,?,?)");
    $mapaIds = [];
    foreach ($nodos as $n) {
        $stmtNodo->execute([
            $n['tipo'], $n['nombre'], $n['subtitulo'], $n['ip'], $n['grupo'], $n['info_extra'],
            $n['num_puertos'], $n['pos_x'], $n['pos_y'],
        ]);
        $mapaIds[$n['key']] = (int)$db->lastInsertId();
    }
    echo "- Dispositivos cargados: " . count($nodos) . "\n";

    $stmtCon = $db->prepare("INSERT INTO red_conexiones (nodo_origen_id, puerto_origen, nodo_destino_id, puerto_destino) VALUES (?,?,?,?)");
    $conectadas = 0;
    foreach ($conexiones as $c) {
        if (!isset($mapaIds[$c['origen']], $mapaIds[$c['destino']])) continue;
        $stmtCon->execute([$mapaIds[$c['origen']], $c['puerto_origen'], $mapaIds[$c['destino']], $c['puerto_destino']]);
        $conectadas++;
    }
    echo "- Conexiones troncales cargadas: $conectadas\n";
    echo "- Dispositivos finales (PCs, APs, cámaras, etc.) cargados con su número de puerto de referencia pero SIN conectar todavía: revisalos y conectalos arrastrando en el diagrama.\n";

    echo "\n=== Migración completada. Ya podés borrar este archivo (migrar_diagrama_red.php). ===\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
