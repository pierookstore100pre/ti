<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>
<?php
$p = $producto;
$accion = $p ? 'admin/productos/actualizar/' . $p['id'] : 'admin/productos/guardar';
$v = fn($campo, $def = '') => old($campo, $p[$campo] ?? $def);
?>
<h1 class="h3 mb-3"><?= esc($titulo) ?></h1>

<form method="post" action="<?= base_url($accion) ?>" enctype="multipart/form-data" class="row g-3 bg-white p-4 rounded border">
    <?= csrf_field() ?>
    <div class="col-md-8">
        <label class="form-label">Nombre *</label>
        <input name="nombre" value="<?= esc($v('nombre')) ?>" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Precio (con IGV) *</label>
        <div class="input-group"><span class="input-group-text">S/</span>
            <input type="number" step="0.01" min="0.01" name="precio" value="<?= esc($v('precio')) ?>" class="form-control" required>
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Categoría *</label>
        <select name="categoria_id" class="form-select" required>
            <option value="">Seleccione...</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $v('categoria_id') == $c['id'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Marca</label>
        <select name="marca_id" class="form-select">
            <option value="">Sin marca</option>
            <?php foreach ($marcas as $m): ?>
                <option value="<?= $m['id'] ?>" <?= $v('marca_id') == $m['id'] ? 'selected' : '' ?>><?= esc($m['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Stock</label>
        <input type="number" min="0" name="stock" value="<?= esc($v('stock', 0)) ?>" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Descripción breve</label>
        <input name="descripcion" value="<?= esc($v('descripcion')) ?>" class="form-control">
    </div>
    <div class="col-12">
        <label class="form-label">Descripción larga (voltajes, amperajes, especificaciones...)</label>
        <textarea name="descripcion_larga" rows="4" class="form-control"><?= esc($v('descripcion_larga')) ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Imagen (JPG, PNG o WEBP, máx. 2 MB)</label>
        <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp" class="form-control">
        <?php if (! empty($p['imagen'])): ?><img src="<?= imagen_producto($p['imagen']) ?>" height="60" class="mt-2 rounded border"><?php endif; ?>
    </div>
    <div class="col-md-6 pt-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="a_pedido" id="a_pedido" <?= $v('a_pedido', 0) ? 'checked' : '' ?>>
            <label class="form-check-label" for="a_pedido">Producto "A Pedido"</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="activo" id="activo" <?= $v('activo', 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="activo">Activo (visible en el catálogo)</label>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Guardar</button>
        <a href="<?= base_url('admin/productos') ?>" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

<?= $this->endSection() ?>
