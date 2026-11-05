<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>

<h1 class="h3 mb-3">Catálogo de productos</h1>

<form method="get" action="<?= base_url('catalogo') ?>" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="search" name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Buscar por nombre...">
    </div>
    <div class="col-md-3">
        <select name="categoria" class="form-select">
            <option value="0">Todas las categorías</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $cat == $c['id'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="marca" class="form-select">
            <option value="0">Todas las marcas</option>
            <?php foreach ($marcas as $m): ?>
                <option value="<?= $m['id'] ?>" <?= $marca == $m['id'] ? 'selected' : '' ?>><?= esc($m['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary"><i class="bi bi-search"></i> Buscar</button>
    </div>
</form>

<?php if (! $productos): ?>
    <div class="alert alert-info">No se encontraron productos con esos filtros.</div>
<?php endif; ?>

<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
    <?php foreach ($productos as $p): $estado = estado_stock($p); ?>
        <div class="col">
            <div class="card card-producto h-100">
                <img src="<?= imagen_producto($p['imagen']) ?>" class="card-img-top" alt="<?= esc($p['nombre']) ?>">
                <div class="card-body d-flex flex-column">
                    <small class="text-muted"><?= esc($p['categoria']) ?><?= $p['marca'] ? ' · ' . esc($p['marca']) : '' ?></small>
                    <h2 class="h6 mt-1"><?= esc($p['nombre']) ?></h2>
                    <p class="fs-5 fw-bold text-primary mb-1"><?= soles($p['precio']) ?></p>
                    <span class="badge bg-<?= clase_stock($estado) ?> align-self-start mb-3"><?= $estado ?></span>
                    <a href="<?= base_url('producto/' . $p['id']) ?>" class="btn btn-outline-primary btn-sm mt-auto">Ver detalle</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-4"><?= $pager->links() ?></div>

<?= $this->endSection() ?>
