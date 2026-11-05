<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?php $estado = estado_stock($producto); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('catalogo') ?>">Catálogo</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('catalogo?categoria=' . $producto['categoria_id']) ?>"><?= esc($producto['categoria']) ?></a></li>
        <li class="breadcrumb-item active"><?= esc($producto['nombre']) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-5">
        <img src="<?= imagen_producto($producto['imagen']) ?>" class="img-fluid rounded border bg-white p-2" alt="<?= esc($producto['nombre']) ?>">
    </div>
    <div class="col-md-7">
        <h1 class="h3"><?= esc($producto['nombre']) ?></h1>
        <p class="text-muted mb-1">Marca: <?= esc($producto['marca'] ?? 'Sin marca') ?></p>
        <p class="display-6 text-primary mb-0"><?= soles($producto['precio']) ?></p>
        <small class="text-muted">Precio con IGV incluido</small>

        <p class="mt-3"><span class="badge bg-<?= clase_stock($estado) ?> fs-6"><?= $estado ?></span></p>
        <p><?= esc($producto['descripcion']) ?></p>

        <?php if (! empty($producto['descripcion_larga'])): ?>
            <h2 class="h6">Especificaciones</h2>
            <p><?= nl2br(esc($producto['descripcion_larga'])) ?></p>
        <?php endif; ?>

        <?php if ($estado === 'Agotado'): ?>
            <button class="btn btn-secondary" disabled>Agotado</button>
        <?php elseif (! $usuario): ?>
            <a href="<?= base_url('login') ?>" class="btn btn-primary">Inicia sesión para comprar</a>
        <?php elseif (in_array($usuario['rol'], ['Cliente'], true)): ?>
            <form method="post" action="<?= base_url('carrito/agregar/' . $producto['id']) ?>" class="row g-2 align-items-center">
                <?= csrf_field() ?>
                <div class="col-auto"><input type="number" name="cantidad" value="1" min="1" class="form-control" style="width:90px"></div>
                <div class="col-auto">
                    <button class="btn btn-success"><i class="bi bi-cart-plus"></i> Añadir al carrito</button>
                </div>
            </form>
            <?php if ($estado === 'A Pedido'): ?>
                <small class="text-muted d-block mt-2">Producto a pedido: el pedido quedará "Pendiente" hasta confirmar el tiempo de entrega.</small>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
