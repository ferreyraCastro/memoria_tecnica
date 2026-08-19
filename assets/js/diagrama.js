(function () {
  const API = 'diagrama_api.php';
  const PUEDE_EDITAR = !!window.PUEDE_EDITAR;

  const TIPOS = {
    pc:         { label: 'PC / Notebook',      icono: 'bi-pc-display',       badge: 'tipo-badge-pc',        puertos: 1 },
    switch:     { label: 'Switch',              icono: 'bi-hdd-network',      badge: 'tipo-badge-switch',    puertos: 24 },
    router:     { label: 'Router',              icono: 'bi-router',           badge: 'tipo-badge-router',    puertos: 5 },
    modem:      { label: 'Módem',               icono: 'bi-globe',            badge: 'tipo-badge-modem',     puertos: 2 },
    ap:         { label: 'Access Point',        icono: 'bi-wifi',             badge: 'tipo-badge-ap',        puertos: 1 },
    impresora:  { label: 'Impresora',           icono: 'bi-printer',          badge: 'tipo-badge-impresora', puertos: 1 },
    camara:     { label: 'Cámara',              icono: 'bi-camera-video',     badge: 'tipo-badge-camara',    puertos: 1 },
    servidor:   { label: 'Servidor',            icono: 'bi-server',           badge: 'tipo-badge-servidor',  puertos: 8 },
    lector:     { label: 'Lector biométrico',   icono: 'bi-fingerprint',      badge: 'tipo-badge-lector',    puertos: 1 },
    conector:   { label: 'Conector / roseta',   icono: 'bi-plug',             badge: 'tipo-badge-conector',  puertos: 1 },
    otro:       { label: 'Otro dispositivo',    icono: 'bi-hdd',              badge: 'tipo-badge-otro',      puertos: 1 },
  };

  const viewport = document.getElementById('diagramaViewport');
  const canvas = document.getElementById('diagramaCanvas');
  const svg = document.getElementById('diagramaSvg');

  let nodos = {};       // id -> {data, el}
  let conexiones = {};  // id -> data
  let equiposDisponibles = []; // cache de Equipos/PCs para vincular
  let scale = 1;
  let dragState = null;
  let connectState = null;
  let selectedNodeId = null;

  // ---------- Utilidades de coordenadas ----------
  function mouseToCanvasPoint(evt) {
    const vp = viewport.getBoundingClientRect();
    return {
      x: (evt.clientX - vp.left + viewport.scrollLeft) / scale,
      y: (evt.clientY - vp.top + viewport.scrollTop) / scale,
    };
  }

  function elementCenterInCanvas(el) {
    const vp = viewport.getBoundingClientRect();
    const r = el.getBoundingClientRect();
    return {
      x: (r.left + r.width / 2 - vp.left + viewport.scrollLeft) / scale,
      y: (r.top + r.height / 2 - vp.top + viewport.scrollTop) / scale,
    };
  }

  function ajax(accion, data) {
    const params = new URLSearchParams(data || {});
    params.set('accion', accion);
    return fetch(API, { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: params.toString() })
      .then(r => r.json());
  }

  // ---------- Render de nodos ----------
  function crearNodoDOM(nodo) {
    const info = TIPOS[nodo.tipo] || TIPOS.otro;
    const div = document.createElement('div');
    div.className = 'nodo-red';
    div.style.left = (nodo.pos_x || 0) + 'px';
    div.style.top = (nodo.pos_y || 0) + 'px';
    div.dataset.id = nodo.id;

    const vinculado = !!nodo.equipo_id;
    const nombreMostrar = (vinculado && nodo.eq_nombre) ? nodo.eq_nombre : nodo.nombre;
    const subMostrar = vinculado
      ? [nodo.eq_piso, nodo.eq_aula || nodo.eq_sala].filter(Boolean).join(' · ')
      : nodo.subtitulo;

    const detalles = [];
    if (vinculado) {
      if (nodo.eq_ip) detalles.push('IP: ' + nodo.eq_ip);
      if (nodo.eq_usuario) detalles.push(nodo.eq_usuario);
      if (nodo.eq_anydesk) detalles.push('AnyDesk: ' + nodo.eq_anydesk);
    } else {
      if (nodo.ip) detalles.push('IP: ' + nodo.ip);
      if (nodo.grupo) detalles.push(nodo.grupo);
    }
    if (nodo.info_extra) detalles.push(nodo.info_extra);

    let conexionBadge = '';
    if (vinculado && nodo.eq_tipo_conexion) {
      const esWifi = nodo.eq_tipo_conexion === 'wifi';
      conexionBadge = `<span class="badge-conexion ${esWifi ? 'badge-wifi' : 'badge-eth'}"><i class="bi ${esWifi ? 'bi-wifi' : 'bi-ethernet'}"></i> ${esWifi ? 'Wi-Fi' : 'Cableada'}</span>`;
    }
    const vinculoIcono = vinculado
      ? `<a href="../equipos/ver.php?id=${nodo.equipo_id}" target="_blank" class="nodo-red-vinculo" title="Ver equipo en el inventario"><i class="bi bi-link-45deg"></i></a>`
      : '';

    let puertosHtml = '';
    const n = Math.max(1, parseInt(nodo.num_puertos || 1));
    for (let p = 1; p <= n; p++) {
      puertosHtml += `<div class="puerto-red" data-nodo="${nodo.id}" data-puerto="${p}" title="Puerto ${p}">${n > 1 ? p : ''}</div>`;
    }

    div.innerHTML = `
      <div class="nodo-red-header">
        <div class="nodo-red-icono ${info.badge}"><i class="bi ${info.icono}"></i></div>
        <div class="flex-grow-1 overflow-hidden">
          <div class="nodo-red-titulo">${escapeHtml(nombreMostrar)}</div>
          ${subMostrar ? `<div class="nodo-red-sub">${escapeHtml(subMostrar)}</div>` : ''}
        </div>
        ${vinculoIcono}
        ${PUEDE_EDITAR ? `<button type="button" class="nodo-red-editar" title="Editar"><i class="bi bi-pencil"></i></button>` : ''}
      </div>
      ${conexionBadge ? `<div class="px-2 pt-1">${conexionBadge}</div>` : ''}
      ${detalles.length ? `<div class="nodo-red-body">${detalles.map(d => `<div>${escapeHtml(d)}</div>`).join('')}</div>` : ''}
      <div class="nodo-red-puertos">${puertosHtml}</div>
    `;

    canvas.appendChild(div);
    nodos[nodo.id] = { data: nodo, el: div };

    if (PUEDE_EDITAR) {
      div.addEventListener('mousedown', onNodeMouseDown);
      div.querySelector('.nodo-red-editar').addEventListener('click', (e) => {
        e.stopPropagation();
        abrirModalNodo(nodo.id);
      });
      div.querySelectorAll('.puerto-red').forEach(p => p.addEventListener('mousedown', onPortMouseDown));
    }
    return div;
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  }

  // ---------- Drag de nodos ----------
  function onNodeMouseDown(evt) {
    if (evt.target.closest('.puerto-red') || evt.target.closest('.nodo-red-editar')) return;
    const el = evt.currentTarget;
    const id = el.dataset.id;
    selectedNodeId = id;
    const startX = evt.clientX, startY = evt.clientY;
    const origLeft = parseFloat(el.style.left) || 0;
    const origTop = parseFloat(el.style.top) || 0;
    dragState = { id, el, startX, startY, origLeft, origTop, moved: false };
    evt.preventDefault();
  }

  document.addEventListener('mousemove', function (evt) {
    if (dragState) {
      const dx = (evt.clientX - dragState.startX) / scale;
      const dy = (evt.clientY - dragState.startY) / scale;
      if (Math.abs(dx) > 2 || Math.abs(dy) > 2) dragState.moved = true;
      const newLeft = Math.max(0, dragState.origLeft + dx);
      const newTop = Math.max(0, dragState.origTop + dy);
      dragState.el.style.left = newLeft + 'px';
      dragState.el.style.top = newTop + 'px';
      redrawConexiones();
    } else if (connectState) {
      const p = mouseToCanvasPoint(evt);
      connectState.ghost.setAttribute('d', `M ${connectState.origen.x},${connectState.origen.y} L ${p.x},${p.y}`);
    }
  });

  document.addEventListener('mouseup', function (evt) {
    if (dragState) {
      const ds = dragState;
      dragState = null;
      if (ds.moved) {
        const left = parseFloat(ds.el.style.left) || 0;
        const top = parseFloat(ds.el.style.top) || 0;
        nodos[ds.id].data.pos_x = left;
        nodos[ds.id].data.pos_y = top;
        ajax('mover_nodo', { id: ds.id, pos_x: Math.round(left), pos_y: Math.round(top) });
      }
    }
    if (connectState) {
      const cs = connectState;
      connectState = null;
      cs.ghost.remove();
      const target = document.elementFromPoint(evt.clientX, evt.clientY);
      const puertoDestino = target && target.closest ? target.closest('.puerto-red') : null;
      if (puertoDestino) {
        const nodoDestino = puertoDestino.dataset.nodo;
        const puertoDestNum = puertoDestino.dataset.puerto;
        if (!(nodoDestino === cs.nodoOrigen && puertoDestNum === cs.puertoOrigen)) {
          ajax('crear_conexion', {
            nodo_origen_id: cs.nodoOrigen, puerto_origen: cs.puertoOrigen,
            nodo_destino_id: nodoDestino, puerto_destino: puertoDestNum,
          }).then(res => {
            if (res.ok) {
              conexiones[res.id] = {
                id: res.id, nodo_origen_id: cs.nodoOrigen, puerto_origen: cs.puertoOrigen,
                nodo_destino_id: nodoDestino, puerto_destino: puertoDestNum,
              };
              redrawConexiones();
              actualizarEstadoPuertos();
            }
          });
        }
      }
    }
  });

  // ---------- Conexiones ----------
  function onPortMouseDown(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    const el = evt.currentTarget;
    const origen = elementCenterInCanvas(el);
    const ghost = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    ghost.setAttribute('class', 'ghost-linea');
    ghost.setAttribute('d', `M ${origen.x},${origen.y} L ${origen.x},${origen.y}`);
    svg.appendChild(ghost);
    connectState = { nodoOrigen: el.dataset.nodo, puertoOrigen: el.dataset.puerto, origen, ghost };
  }

  function resaltarPuertosDeConexion(c, activar) {
    const pOrigen = canvas.querySelector(`.puerto-red[data-nodo="${c.nodo_origen_id}"][data-puerto="${c.puerto_origen}"]`);
    const pDestino = canvas.querySelector(`.puerto-red[data-nodo="${c.nodo_destino_id}"][data-puerto="${c.puerto_destino}"]`);
    [pOrigen, pDestino].forEach(p => { if (p) p.classList.toggle('puerto-resaltado', activar); });
  }

  function redrawConexiones() {
    svg.querySelectorAll('.conexion-linea').forEach(l => l.remove());
    Object.values(conexiones).forEach(c => {
      const pOrigen = canvas.querySelector(`.puerto-red[data-nodo="${c.nodo_origen_id}"][data-puerto="${c.puerto_origen}"]`);
      const pDestino = canvas.querySelector(`.puerto-red[data-nodo="${c.nodo_destino_id}"][data-puerto="${c.puerto_destino}"]`);
      if (!pOrigen || !pDestino) return;
      const a = elementCenterInCanvas(pOrigen);
      const b = elementCenterInCanvas(pDestino);
      const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('class', 'conexion-linea');
      path.setAttribute('d', `M ${a.x},${a.y} L ${b.x},${b.y}`);
      path.dataset.id = c.id;
      path.style.pointerEvents = 'stroke';
      path.addEventListener('mouseenter', () => resaltarPuertosDeConexion(c, true));
      path.addEventListener('mouseleave', () => resaltarPuertosDeConexion(c, false));
      if (PUEDE_EDITAR) {
        path.addEventListener('click', () => {
          Swal.fire({
            title: '¿Eliminar esta conexión?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
          }).then(res => {
            if (res.isConfirmed) {
              ajax('eliminar_conexion', { id: c.id }).then(r => {
                if (r.ok) { delete conexiones[c.id]; redrawConexiones(); actualizarEstadoPuertos(); }
              });
            }
          });
        });
      }
      svg.appendChild(path);
    });
  }

  // ---------- Estado de puertos (conectado / libre) ----------
  function nombreVisibleNodo(data) {
    if (!data) return '?';
    return (data.equipo_id && data.eq_nombre) ? data.eq_nombre : data.nombre;
  }

  function actualizarEstadoPuertos() {
    const mapa = {};
    Object.values(conexiones).forEach(c => {
      mapa[c.nodo_origen_id + ':' + c.puerto_origen] = { nodoId: c.nodo_destino_id, puerto: c.puerto_destino };
      mapa[c.nodo_destino_id + ':' + c.puerto_destino] = { nodoId: c.nodo_origen_id, puerto: c.puerto_origen };
    });
    canvas.querySelectorAll('.puerto-red').forEach(p => {
      const nodoId = p.dataset.nodo;
      const puerto = p.dataset.puerto;
      const conex = mapa[nodoId + ':' + puerto];
      if (conex) {
        const otro = nodos[conex.nodoId];
        p.classList.add('puerto-conectado');
        p.title = `Puerto ${puerto} — conectado a: ${nombreVisibleNodo(otro && otro.data)} (puerto ${conex.puerto})`;
      } else {
        p.classList.remove('puerto-conectado');
        p.title = `Puerto ${puerto} — sin conexión`;
      }
    });
  }

  // ---------- Modal de nodo ----------
  const modalEl = document.getElementById('modalNodo');
  const modal = modalEl ? new bootstrap.Modal(modalEl) : null;
  const selEquipo = document.getElementById('nodoEquipoId');
  const grupoVinculo = document.getElementById('grupoVinculoEquipo');

  function poblarSelectEquipos() {
    if (!selEquipo) return;
    selEquipo.innerHTML = '<option value="">— Sin vincular —</option>' + equiposDisponibles.map(e => {
      const ubic = [e.piso, e.aula || e.sala].filter(Boolean).join(' · ');
      const conexion = e.tipo_conexion === 'wifi' ? 'Wi-Fi' : 'Cableada';
      return `<option value="${e.id}">${escapeHtml(e.nombre_pc)}${ubic ? ' — ' + escapeHtml(ubic) : ''} (${conexion})</option>`;
    }).join('');
  }

  function actualizarVisibilidadVinculo() {
    if (!grupoVinculo) return;
    const tipo = document.getElementById('nodoTipo').value;
    grupoVinculo.classList.toggle('d-none', tipo !== 'pc');
  }
  document.getElementById('nodoTipo')?.addEventListener('change', actualizarVisibilidadVinculo);
  selEquipo?.addEventListener('change', function () {
    if (!this.value) return;
    const eq = equiposDisponibles.find(e => String(e.id) === this.value);
    if (eq && !document.getElementById('nodoNombre').value.trim()) {
      document.getElementById('nodoNombre').value = eq.nombre_pc;
    }
  });

  window.abrirModalNodoNuevo = function (tipo) {
    document.getElementById('nodoId').value = '';
    document.getElementById('nodoTipo').value = tipo || 'otro';
    document.getElementById('nodoNombre').value = '';
    document.getElementById('nodoSubtitulo').value = '';
    document.getElementById('nodoIp').value = '';
    document.getElementById('nodoGrupo').value = '';
    document.getElementById('nodoInfoExtra').value = '';
    document.getElementById('nodoNumPuertos').value = (TIPOS[tipo] || TIPOS.otro).puertos;
    if (selEquipo) selEquipo.value = '';
    actualizarVisibilidadVinculo();
    document.getElementById('btnEliminarNodo').classList.add('d-none');
    document.getElementById('modalNodoTitulo').textContent = 'Nuevo dispositivo';
    modal.show();
  };

  function abrirModalNodo(id) {
    const nodo = nodos[id].data;
    document.getElementById('nodoId').value = nodo.id;
    document.getElementById('nodoTipo').value = nodo.tipo;
    document.getElementById('nodoNombre').value = nodo.nombre || '';
    document.getElementById('nodoSubtitulo').value = nodo.subtitulo || '';
    document.getElementById('nodoIp').value = nodo.ip || '';
    document.getElementById('nodoGrupo').value = nodo.grupo || '';
    document.getElementById('nodoInfoExtra').value = nodo.info_extra || '';
    document.getElementById('nodoNumPuertos').value = nodo.num_puertos || 1;
    if (selEquipo) selEquipo.value = nodo.equipo_id || '';
    actualizarVisibilidadVinculo();
    document.getElementById('btnEliminarNodo').classList.remove('d-none');
    document.getElementById('modalNodoTitulo').textContent = 'Editar dispositivo';
    modal.show();
  }

  document.getElementById('formNodo')?.addEventListener('submit', function (evt) {
    evt.preventDefault();
    const id = document.getElementById('nodoId').value;
    const payload = {
      tipo: document.getElementById('nodoTipo').value,
      nombre: document.getElementById('nodoNombre').value.trim(),
      subtitulo: document.getElementById('nodoSubtitulo').value.trim(),
      ip: document.getElementById('nodoIp').value.trim(),
      grupo: document.getElementById('nodoGrupo').value.trim(),
      info_extra: document.getElementById('nodoInfoExtra').value.trim(),
      num_puertos: document.getElementById('nodoNumPuertos').value,
      equipo_id: selEquipo ? selEquipo.value : '',
    };
    if (!payload.nombre) return;

    if (id) {
      payload.id = id;
      ajax('editar_nodo', payload).then(res => {
        if (res.ok) {
          const oldEl = nodos[id].el;
          const left = oldEl.style.left, top = oldEl.style.top;
          oldEl.remove();
          const nodoActualizado = res.nodo || Object.assign(nodos[id].data, payload);
          nodoActualizado.pos_x = parseFloat(left);
          nodoActualizado.pos_y = parseFloat(top);
          const el = crearNodoDOM(nodoActualizado);
          el.style.left = left; el.style.top = top;
          redrawConexiones();
          actualizarEstadoPuertos();
          modal.hide();
        }
      });
    } else {
      const vpRect = viewport.getBoundingClientRect();
      payload.pos_x = Math.round((viewport.scrollLeft + vpRect.width / 2) / scale - 84);
      payload.pos_y = Math.round((viewport.scrollTop + vpRect.height / 2) / scale - 40);
      ajax('crear_nodo', payload).then(res => {
        if (res.ok) {
          crearNodoDOM(res.nodo);
          actualizarEstadoPuertos();
          modal.hide();
        }
      });
    }
  });

  document.getElementById('btnEliminarNodo')?.addEventListener('click', function () {
    const id = document.getElementById('nodoId').value;
    if (!id) return;
    Swal.fire({
      title: '¿Eliminar este dispositivo?',
      text: 'También se eliminarán sus conexiones.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    }).then(res => {
      if (res.isConfirmed) {
        ajax('eliminar_nodo', { id }).then(r => {
          if (r.ok) {
            nodos[id].el.remove();
            delete nodos[id];
            Object.keys(conexiones).forEach(cid => {
              const c = conexiones[cid];
              if (String(c.nodo_origen_id) === String(id) || String(c.nodo_destino_id) === String(id)) delete conexiones[cid];
            });
            redrawConexiones();
            actualizarEstadoPuertos();
            modal.hide();
          }
        });
      }
    });
  });

  // ---------- Zoom ----------
  window.zoomDiagrama = function (delta) {
    scale = Math.min(1.5, Math.max(0.4, scale + delta));
    canvas.style.transform = `scale(${scale})`;
    document.getElementById('zoomLabel').textContent = Math.round(scale * 100) + '%';
    redrawConexiones();
  };
  window.zoomResetDiagrama = function () {
    scale = 1;
    canvas.style.transform = 'scale(1)';
    document.getElementById('zoomLabel').textContent = '100%';
    redrawConexiones();
  };

  // ---------- Exportar a PDF ----------
  window.exportarPDFDiagrama = async function () {
    const nodeEls = Array.from(document.querySelectorAll('.nodo-red'));
    if (!nodeEls.length) { Swal.fire('No hay dispositivos para exportar.'); return; }
    if (typeof html2canvas === 'undefined' || !window.jspdf) {
      Swal.fire('error', 'No se pudieron cargar las librerías de exportación. Verificá tu conexión a internet.', 'error');
      return;
    }

    const prevScale = scale;
    if (scale !== 1) { window.zoomResetDiagrama(); await new Promise(r => setTimeout(r, 80)); }

    let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
    nodeEls.forEach(el => {
      const l = el.offsetLeft, t = el.offsetTop, w = el.offsetWidth, h = el.offsetHeight;
      minX = Math.min(minX, l); minY = Math.min(minY, t);
      maxX = Math.max(maxX, l + w); maxY = Math.max(maxY, t + h);
    });
    const pad = 60;
    minX = Math.max(0, minX - pad); minY = Math.max(0, minY - pad);
    maxX += pad; maxY += pad;
    const boxW = Math.round(maxX - minX), boxH = Math.round(maxY - minY);

    Swal.fire({ title: 'Generando PDF...', text: 'Puede tardar unos segundos', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    try {
      const RENDER_SCALE = 1.5;
      const fullShot = await html2canvas(canvas, {
        backgroundColor: '#ffffff', scale: RENDER_SCALE,
        width: canvas.scrollWidth, height: canvas.scrollHeight,
      });

      const cropCanvas = document.createElement('canvas');
      cropCanvas.width = boxW * RENDER_SCALE;
      cropCanvas.height = boxH * RENDER_SCALE;
      const ctx = cropCanvas.getContext('2d');
      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, cropCanvas.width, cropCanvas.height);
      ctx.drawImage(
        fullShot,
        minX * RENDER_SCALE, minY * RENDER_SCALE, boxW * RENDER_SCALE, boxH * RENDER_SCALE,
        0, 0, cropCanvas.width, cropCanvas.height
      );

      const imgData = cropCanvas.toDataURL('image/png');
      const { jsPDF } = window.jspdf;
      const mmW = boxW * 25.4 / 96;
      const mmH = boxH * 25.4 / 96;
      const margin = 12;
      const orientation = mmW >= mmH ? 'l' : 'p';
      const doc = new jsPDF({ orientation, unit: 'mm', format: [mmW + margin * 2, mmH + margin * 2 + 8] });
      doc.setFontSize(11);
      doc.text('Diagrama de red — Colegio San José', margin, margin - 2);
      doc.addImage(imgData, 'PNG', margin, margin + 6, mmW, mmH);
      doc.save('diagrama-red-colegio-san-jose.pdf');
      Swal.close();
    } catch (err) {
      Swal.fire('error', 'No se pudo generar el PDF: ' + err.message, 'error');
    } finally {
      if (prevScale !== 1) {
        scale = prevScale;
        canvas.style.transform = `scale(${scale})`;
        document.getElementById('zoomLabel').textContent = Math.round(scale * 100) + '%';
        redrawConexiones();
      }
    }
  };

  // ---------- Carga inicial ----------
  if (PUEDE_EDITAR) {
    ajax('equipos_disponibles').then(res => {
      if (res.ok) { equiposDisponibles = res.equipos; poblarSelectEquipos(); }
    });
  }
  ajax('listar').then(res => {
    if (!res.ok) return;
    res.nodos.forEach(n => crearNodoDOM(n));
    res.conexiones.forEach(c => { conexiones[c.id] = c; });
    redrawConexiones();
    actualizarEstadoPuertos();
  });
})();
