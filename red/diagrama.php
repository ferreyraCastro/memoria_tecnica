<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$pageTitle = 'Diagrama de red';
$verCss = @filemtime(__DIR__ . '/../assets/css/diagrama.css') ?: time();
$extraHead = '<link href="' . baseUrl() . 'assets/css/diagrama.css?v=' . $verCss . '" rel="stylesheet">';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-header">
  <div>
    <h1><i class="bi bi-diagram-3"></i> Diagrama de red interactivo</h1>
    <div class="subtitle">Estructura de red del colegio: dispositivos, switches y sus conexiones por puerto</div>
  </div>
  <a href="index.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver al listado</a>
</div>

<div class="card-app mb-3">
  <div class="p-3">
    <div class="diagrama-toolbar">
      <?php if (canEdit()): ?>
      <div class="dropdown">
        <button class="btn btn-brand dropdown-toggle" type="button" data-bs-toggle="dropdown">
          <i class="bi bi-plus-lg"></i> Agregar dispositivo
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('pc'); return false;"><i class="bi bi-pc-display me-2"></i>PC / Notebook</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('switch'); return false;"><i class="bi bi-hdd-network me-2"></i>Switch</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('router'); return false;"><i class="bi bi-router me-2"></i>Router</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('modem'); return false;"><i class="bi bi-globe me-2"></i>Módem</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('ap'); return false;"><i class="bi bi-wifi me-2"></i>Access Point</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('impresora'); return false;"><i class="bi bi-printer me-2"></i>Impresora</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('camara'); return false;"><i class="bi bi-camera-video me-2"></i>Cámara</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('servidor'); return false;"><i class="bi bi-server me-2"></i>Servidor</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('lector'); return false;"><i class="bi bi-fingerprint me-2"></i>Lector biométrico</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('conector'); return false;"><i class="bi bi-plug me-2"></i>Conector / roseta</a></li>
          <li><a class="dropdown-item" href="#" onclick="abrirModalNodoNuevo('otro'); return false;"><i class="bi bi-hdd me-2"></i>Otro</a></li>
        </ul>
      </div>
      <?php endif; ?>
      <div class="btn-group">
        <button class="btn btn-outline-secondary" onclick="zoomDiagrama(-0.1)" title="Alejar"><i class="bi bi-dash-lg"></i></button>
        <button class="btn btn-outline-secondary" id="zoomLabel" onclick="zoomResetDiagrama()" style="min-width:60px;">100%</button>
        <button class="btn btn-outline-secondary" onclick="zoomDiagrama(0.1)" title="Acercar"><i class="bi bi-plus-lg"></i></button>
      </div>
      <button class="btn btn-outline-secondary" onclick="exportarPDFDiagrama()"><i class="bi bi-file-earmark-pdf"></i> Exportar a PDF</button>
      <div class="text-secondary small ms-auto">
        <?php if (canEdit()): ?>
          <i class="bi bi-info-circle"></i> Arrastrá un dispositivo para moverlo. Arrastrá desde un puerto (círculo) hasta otro puerto para conectar. Hacé clic en una línea para borrarla.
        <?php else: ?>
          <i class="bi bi-eye"></i> Modo solo lectura.
        <?php endif; ?>
      </div>
    </div>

    <div class="diagrama-leyenda mb-2">
      <span><i class="bi bi-square-fill text-primary"></i> PC</span>
      <span><i class="bi bi-square-fill text-success"></i> Switch</span>
      <span><i class="bi bi-square-fill" style="color:#a21caf"></i> Router</span>
      <span><i class="bi bi-square-fill" style="color:#c2410c"></i> Módem</span>
      <span><i class="bi bi-square-fill" style="color:#0284c7"></i> Access Point</span>
      <span><i class="bi bi-square-fill text-secondary"></i> Impresora</span>
      <span><i class="bi bi-square-fill text-danger"></i> Cámara</span>
      <span><i class="bi bi-square-fill" style="color:#1e293b"></i> Servidor</span>
      <span class="ms-3"><i class="bi bi-circle-fill" style="color:#059669"></i> Puerto conectado</span>
      <span><i class="bi bi-circle-fill" style="color:#94a3b8"></i> Puerto libre</span>
    </div>

    <div class="diagrama-viewport" id="diagramaViewport">
      <div class="diagrama-canvas" id="diagramaCanvas">
        <svg class="diagrama-svg" id="diagramaSvg"></svg>
      </div>
    </div>
  </div>
</div>

<!-- Modal crear/editar nodo -->
<div class="modal fade" id="modalNodo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formNodo">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNodoTitulo">Nuevo dispositivo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="nodoId">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Tipo</label>
              <select id="nodoTipo" class="form-select">
                <option value="pc">PC / Notebook</option>
                <option value="switch">Switch</option>
                <option value="router">Router</option>
                <option value="modem">Módem</option>
                <option value="ap">Access Point</option>
                <option value="impresora">Impresora</option>
                <option value="camara">Cámara</option>
                <option value="servidor">Servidor</option>
                <option value="lector">Lector biométrico</option>
                <option value="conector">Conector / roseta</option>
                <option value="otro">Otro</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">N° de puertos</label>
              <input type="number" id="nodoNumPuertos" class="form-control" min="1" max="48" value="1">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Nombre *</label>
              <input type="text" id="nodoNombre" class="form-control" required placeholder="Ej: PC - Secretaría">
            </div>
            <div class="col-12 d-none" id="grupoVinculoEquipo">
              <label class="form-label fw-semibold"><i class="bi bi-link-45deg"></i> Vincular con equipo del inventario</label>
              <select id="nodoEquipoId" class="form-select">
                <option value="">— Sin vincular —</option>
              </select>
              <div class="form-text">Si lo vinculás, el diagrama va a mostrar siempre el nombre, la IP y el tipo de conexión (Wi-Fi / cableada) actuales de ese equipo, tomados de "Equipos / PCs" — si lo editás ahí, se actualiza acá también.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Subtítulo / hostname</label>
              <input type="text" id="nodoSubtitulo" class="form-control" placeholder="Ej: DESKTOP-ABC123">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">IP</label>
              <input type="text" id="nodoIp" class="form-control" placeholder="192.168.x.x">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Grupo / red</label>
              <input type="text" id="nodoGrupo" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Información adicional</label>
              <textarea id="nodoInfoExtra" class="form-control" rows="2" placeholder="Notas, número de puerto de referencia, etc."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" id="btnEliminarNodo" class="btn btn-outline-danger d-none"><i class="bi bi-trash"></i> Eliminar</button>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-brand"><i class="bi bi-check-lg"></i> Guardar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>window.PUEDE_EDITAR = <?= canEdit() ? 'true' : 'false' ?>;</script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<?php $verJs = @filemtime(__DIR__ . '/../assets/js/diagrama.js') ?: time(); ?>
<script src="<?= baseUrl() ?>assets/js/diagrama.js?v=<?= $verJs ?>" defer></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
