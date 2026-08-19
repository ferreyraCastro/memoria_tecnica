<?php /** @var array $form fields expected in scope */ ?>
<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label fw-semibold">Nombre / identificación de la PC *</label>
    <input type="text" name="nombre_pc" class="form-control" required value="<?= h($form['nombre_pc']) ?>" placeholder="Ej: PC-SALA1-05">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-semibold">Dirección MAC</label>
    <input type="text" name="mac" class="form-control" value="<?= h($form['mac']) ?>" placeholder="AA:BB:CC:DD:EE:FF">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-semibold">Dirección IP (DHCP)</label>
    <input type="text" name="ip" class="form-control" value="<?= h($form['ip']) ?>" placeholder="192.168.1.10">
  </div>

  <div class="col-md-4">
    <label class="form-label fw-semibold">Subred</label>
    <input type="text" name="subred" class="form-control" value="<?= h($form['subred']) ?>" placeholder="255.255.255.0">
  </div>
  <div class="col-md-4">
    <label class="form-label fw-semibold">Tipo de conexión</label>
    <select name="tipo_conexion" class="form-select">
      <option value="cableada" <?= $form['tipo_conexion'] === 'cableada' ? 'selected' : '' ?>>Cableada</option>
      <option value="wifi" <?= $form['tipo_conexion'] === 'wifi' ? 'selected' : '' ?>>Wi-Fi</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label fw-semibold">Estado</label>
    <select name="estado" class="form-select">
      <option value="activo" <?= $form['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
      <option value="en_reparacion" <?= $form['estado'] === 'en_reparacion' ? 'selected' : '' ?>>En reparación</option>
      <option value="de_baja" <?= $form['estado'] === 'de_baja' ? 'selected' : '' ?>>De baja</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label fw-semibold">Piso</label>
    <input type="text" name="piso" class="form-control" value="<?= h($form['piso']) ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">Aula</label>
    <input type="text" name="aula" class="form-control" value="<?= h($form['aula']) ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">Sala</label>
    <input type="text" name="sala" class="form-control" value="<?= h($form['sala']) ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">Curso / división</label>
    <input type="text" name="curso" class="form-control" value="<?= h($form['curso']) ?>">
  </div>

  <div class="col-md-6">
    <label class="form-label fw-semibold">Sistema operativo</label>
    <input type="text" name="sistema_operativo" class="form-control" value="<?= h($form['sistema_operativo']) ?>" placeholder="Windows 11 Pro, Ubuntu 22.04...">
  </div>
  <div class="col-md-6">
    <label class="form-label fw-semibold">Usuario asignado</label>
    <input type="text" name="usuario_asignado" class="form-control" value="<?= h($form['usuario_asignado']) ?>">
  </div>

  <div class="col-12">
    <label class="form-label fw-semibold">Claves o información técnica asociada</label>
    <textarea name="claves_info" class="form-control" rows="2" placeholder="Contraseñas locales, BIOS, notas técnicas..."><?= h($form['claves_info']) ?></textarea>
  </div>
  <div class="col-12">
    <label class="form-label fw-semibold">Observaciones</label>
    <textarea name="observaciones" class="form-control" rows="3"><?= h($form['observaciones']) ?></textarea>
  </div>
</div>
