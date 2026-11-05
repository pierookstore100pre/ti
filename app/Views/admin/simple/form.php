<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>
<?php
$accion = $fila ? 'admin/' . $ruta . '/actualizar/' . $fila['id'] : 'admin/' . $ruta . '/guardar';
$v = fn($campo, $def = '') => old($campo, $fila[$campo] ?? $def);
?>
<h1 class="h3 mb-3"><?= $fila ? 'Editar' : 'Nueva' ?> — <?= esc($titulo) ?></h1>

<form method="post" action="<?= base_url($accion) ?>" class="bg-white p-4 rounded border" style="max-width:600px">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label">Nombre *</label>
        <input name="nombre" value="<?= esc($v('nombre')) ?>" class="form-control" required>
    </div>
    <?php if ($descripcion): ?>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" rows="3" class="form-control"><?= esc($v('descripcion')) ?></textarea>
    </div>
    <?php endif; ?>
    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= $v('activo', 1) ? 'checked' : '' ?>>
        <label class="form-check-label" for="activo">Activo</label>
    </div>
    <button class="btn btn-primary">Guardar</button>
    <a href="<?= base_url('admin/' . $ruta) ?>" class="btn btn-outline-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
