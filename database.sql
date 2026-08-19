-- =========================================================
-- Memoria Técnica Digital - Colegio San José
-- Esquema de base de datos MySQL
-- =========================================================

CREATE DATABASE IF NOT EXISTS memoria_tecnica
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE memoria_tecnica;

-- ---------------------------------------------------------
-- Usuarios del sistema (login / roles)
-- ---------------------------------------------------------
CREATE TABLE usuarios_sistema (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  usuario VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','tecnico','lectura') NOT NULL DEFAULT 'tecnico',
  activo TINYINT(1) NOT NULL DEFAULT 1,
  ultimo_acceso DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 1. Gestión de contraseñas y accesos
-- ---------------------------------------------------------
CREATE TABLE categorias_accesos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE accesos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  servicio VARCHAR(150) NOT NULL,
  usuario VARCHAR(150) NOT NULL,
  password_cifrada TEXT NOT NULL,
  iv VARCHAR(64) NOT NULL,
  url VARCHAR(255) NULL,
  categoria_id INT NULL,
  observaciones TEXT NULL,
  fecha_actualizacion DATE NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  creado_por INT NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias_accesos(id) ON DELETE SET NULL,
  FOREIGN KEY (creado_por) REFERENCES usuarios_sistema(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 2. Suscripciones y renovaciones
-- ---------------------------------------------------------
CREATE TABLE suscripciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  proveedor VARCHAR(150) NOT NULL,
  servicio VARCHAR(150) NOT NULL,
  costo DECIMAL(12,2) NULL,
  moneda VARCHAR(10) NOT NULL DEFAULT 'ARS',
  fecha_contratacion DATE NULL,
  fecha_vencimiento DATE NOT NULL,
  periodo_renovacion ENUM('mensual','trimestral','semestral','anual','bianual','otro') NOT NULL DEFAULT 'anual',
  observaciones TEXT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE renovaciones_historial (
  id INT AUTO_INCREMENT PRIMARY KEY,
  suscripcion_id INT NOT NULL,
  fecha_renovacion DATE NOT NULL,
  fecha_vencimiento_anterior DATE NULL,
  fecha_vencimiento_nueva DATE NOT NULL,
  costo DECIMAL(12,2) NULL,
  observaciones TEXT NULL,
  usuario_id INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (suscripcion_id) REFERENCES suscripciones(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios_sistema(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 3. Inventario técnico de computadoras
-- ---------------------------------------------------------
CREATE TABLE equipos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_pc VARCHAR(100) NOT NULL,
  mac VARCHAR(50) NULL,
  ip VARCHAR(50) NULL,
  subred VARCHAR(50) NULL,
  tipo_conexion ENUM('cableada','wifi') NOT NULL DEFAULT 'cableada',
  aula VARCHAR(100) NULL,
  sala VARCHAR(100) NULL,
  piso VARCHAR(50) NULL,
  curso VARCHAR(100) NULL,
  sistema_operativo VARCHAR(100) NULL,
  usuario_asignado VARCHAR(150) NULL,
  anydesk_id VARCHAR(50) NULL,
  anydesk_password_cifrada TEXT NULL,
  anydesk_iv VARCHAR(64) NULL,
  claves_info TEXT NULL,
  observaciones TEXT NULL,
  estado ENUM('activo','en_reparacion','de_baja') NOT NULL DEFAULT 'activo',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE INDEX idx_equipos_mac ON equipos(mac);
CREATE INDEX idx_equipos_ip ON equipos(ip);
CREATE INDEX idx_equipos_aula ON equipos(aula);
CREATE INDEX idx_equipos_piso ON equipos(piso);
CREATE INDEX idx_equipos_curso ON equipos(curso);

-- ---------------------------------------------------------
-- 4. Información de red y Wi-Fi
-- ---------------------------------------------------------
CREATE TABLE red_dispositivos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('access_point','switch','router','modem','otro') NOT NULL DEFAULT 'access_point',
  nombre VARCHAR(150) NOT NULL,
  mac VARCHAR(50) NULL,
  ip VARCHAR(50) NULL,
  subred VARCHAR(50) NULL,
  ssid VARCHAR(150) NULL,
  password_wifi_cifrada TEXT NULL,
  iv VARCHAR(64) NULL,
  ubicacion VARCHAR(150) NULL,
  piso VARCHAR(50) NULL,
  marca_modelo VARCHAR(150) NULL,
  observaciones TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 4b. Diagrama de red interactivo (nodos y conexiones)
-- ---------------------------------------------------------
CREATE TABLE red_nodos (
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
  equipo_id INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE red_conexiones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nodo_origen_id INT NOT NULL,
  puerto_origen INT NOT NULL DEFAULT 1,
  nodo_destino_id INT NOT NULL,
  puerto_destino INT NOT NULL DEFAULT 1,
  etiqueta VARCHAR(100) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nodo_origen_id) REFERENCES red_nodos(id) ON DELETE CASCADE,
  FOREIGN KEY (nodo_destino_id) REFERENCES red_nodos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- 5. Servicio técnico (mantenimientos / reparaciones)
-- ---------------------------------------------------------
CREATE TABLE mantenimientos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipo_id INT NOT NULL,
  fecha DATE NOT NULL,
  tipo ENUM('mantenimiento_preventivo','reparacion','instalacion','otro') NOT NULL DEFAULT 'reparacion',
  problema_detectado TEXT NULL,
  descripcion TEXT NOT NULL,
  componentes_reemplazados VARCHAR(255) NULL,
  repuestos_utilizados VARCHAR(255) NULL,
  tecnico VARCHAR(150) NULL,
  observaciones TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_mantenimientos_equipo ON mantenimientos(equipo_id);
CREATE INDEX idx_mantenimientos_fecha ON mantenimientos(fecha);

-- ---------------------------------------------------------
-- 6. Planos y documentación técnica
-- ---------------------------------------------------------
CREATE TABLE categorias_documentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE documentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT NULL,
  nombre VARCHAR(200) NOT NULL,
  descripcion TEXT NULL,
  archivo_path VARCHAR(255) NOT NULL,
  archivo_original VARCHAR(255) NOT NULL,
  tipo_archivo VARCHAR(50) NULL,
  tamano_bytes BIGINT NULL,
  fecha_subida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  subido_por INT NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias_documentos(id) ON DELETE SET NULL,
  FOREIGN KEY (subido_por) REFERENCES usuarios_sistema(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================================
-- Datos semilla
-- =========================================================

-- Usuario admin por defecto: usuario "admin" / clave "admin123"
-- (el hash corresponde a password_hash('admin123', PASSWORD_DEFAULT))
INSERT INTO usuarios_sistema (nombre, email, usuario, password_hash, rol) VALUES
('Administrador', 'admin@colegio.edu.ar', 'admin', '$2y$12$hCqn3X3E0gOW5usBz7kSION.VtUs8Hy0ERA3YvjeVQR7Is/qswdum', 'admin');

INSERT INTO categorias_accesos (nombre) VALUES
('Hosting'), ('Dominios'), ('Correo institucional'), ('Plataformas educativas'), ('Redes sociales'), ('Otros');

INSERT INTO categorias_documentos (nombre) VALUES
('Planos del colegio'), ('Planos de red'), ('Distribución de aulas'), ('Ubicación de racks'),
('Esquemas de cableado'), ('Fotografías técnicas'), ('Manuales'), ('Documentación de instalaciones'), ('Otros');
