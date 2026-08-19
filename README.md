# Memoria Técnica Digital — Colegio San José

Sistema de gestión y memoria técnica desarrollado en PHP + MySQL + SweetAlert2 + Bootstrap 5.

## Instalación en XAMPP

1. **Copiá esta carpeta** dentro de `C:\xampp\htdocs\`. Si ya existe una carpeta `memoria`, reemplazá su contenido por este.

2. **Iniciá Apache y MySQL** desde el panel de XAMPP.

3. **Creá la base de datos**: abrí phpMyAdmin (`http://localhost/phpmyadmin`), pestaña "Importar", y seleccioná el archivo `database.sql` incluido en esta carpeta. Esto crea la base `memoria_tecnica` con todas las tablas y datos iniciales.

   También podés hacerlo por línea de comandos:
   ```
   C:\xampp\mysql\bin\mysql -u root < database.sql
   ```

4. **Configurá la conexión** (si tu MySQL no usa usuario `root` sin contraseña) en `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'memoria_tecnica');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

5. **Cambiá la clave de cifrado** en el mismo archivo antes de cargar contraseñas reales:
   ```php
   define('APP_ENCRYPTION_KEY', 'CAMBIAR-ESTA-CLAVE-POR-UNA-PROPIA-32BYTES!!');
   ```
   Esta clave cifra las contraseñas guardadas en el módulo de Accesos y las contraseñas de Wi-Fi. Guardala en un lugar seguro: si se pierde o cambia, las contraseñas ya guardadas no se podrán descifrar.

6. **Dale permisos de escritura** a la carpeta `uploads/documentos/` (en Windows normalmente ya funciona, en Linux/Mac correr `chmod -R 775 uploads`).

7. **Entrá al sistema**: `http://localhost/memoria/`

   Usuario inicial:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`

   ⚠️ **Cambiá esta contraseña** apenas ingreses, desde Usuarios del sistema → Editar.

## Estructura del sistema

- `index.php` — Panel principal (dashboard)
- `login.php` / `logout.php` — Autenticación
- `accesos/` — Gestión de contraseñas y accesos (solo administradores)
- `suscripciones/` — Control de suscripciones, vencimientos y renovaciones
- `equipos/` — Inventario técnico de computadoras
- `red/` — Infraestructura de red y Wi-Fi
- `servicio_tecnico/` — Mantenimientos y reparaciones
- `documentacion/` — Planos, manuales y documentación técnica (archivos)
- `usuarios/` — Gestión de usuarios del sistema y roles
- `config/config.php` — Configuración de base de datos y clave de cifrado
- `database.sql` — Esquema completo de la base de datos

## Roles de usuario

- **Administrador**: acceso total, incluida la gestión de contraseñas/accesos y usuarios del sistema.
- **Técnico**: gestión de equipos, red, servicio técnico, suscripciones y documentación (sin acceso al módulo de contraseñas).
- **Solo lectura**: consulta de toda la información, sin poder crear, editar ni eliminar.

## Seguridad

- Las contraseñas de acceso al sistema se guardan con `password_hash` (bcrypt).
- Las contraseñas de servicios/Wi-Fi guardadas en el sistema se cifran de forma reversible con AES-256-CBC para poder mostrarlas cuando se necesitan.
- Todas las consultas usan sentencias preparadas (PDO) para evitar inyección SQL.
- Se recomienda instalar el sistema únicamente en la red interna del colegio o detrás de HTTPS si se expone a internet.

## Próximos pasos sugeridos

- Cargar el inventario real de equipos (podés hacerlo manualmente desde "Equipos → Nuevo equipo", o pedirme que te ayude a generar un importador desde tu planilla Excel actual).
- Cargar las suscripciones vigentes (hosting, dominio, licencias) con sus fechas de vencimiento reales.
- Subir los planos y documentación técnica existente.
- Crear un usuario por cada persona que use el sistema, en vez de compartir el usuario admin.
# memoria_tecnica
