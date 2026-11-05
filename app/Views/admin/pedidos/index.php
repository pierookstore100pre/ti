<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>

<h1 class="h3 mb-3">Pedidos</h1>

<?php if ($usuario['rol'] !== 'Repartidor'): ?>
<div class="mb-3">
    <a href="<?= base_url('admin/pedidos') ?>" class="btn btn-sm btn-outline-dark <?= $filtro === '' ? 'active' : '' ?>">Todos</a>
    <?php foreach (['Pendiente','Pagado','Enviado','Entregado','Cancelado'] as $e): ?>
        <a href="<?= base_url('admin/pedidos?estado=' . $e) ?>" class="btn btn-sm btn-outline-<?= clase_estado_pedido($e) ?> <?= $filtro === $e ? 'active' : '' ?>"><?= $e ?></a>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-info">Mostrando pedidos <strong>Pagados</strong> (por enviar) y <strong>Enviados</strong> (por entregar).</div>
<?php endif; ?>

<?php if (! $pedidos): ?><div class="alert alert-secondary">No hay pedidos.</div><?php endif; ?>

<?php foreach ($pedidos as $p): ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><strong>#<?= $p['id'] ?></strong> · <?= esc($p['nombres'] . ' ' . $p['apellidos']) ?> · <?= esc($p['fecha_venta']) ?></span>
        <span class="badge bg-<?= clase_estado_pedido($p['estado']) ?>"><?= esc($p['estado']) ?></span>
    </div>
    <div class="card-body">
        <ul class="mb-2">
            <?php foreach ($p['items'] as $i): ?><li><?= esc($i['nombre']) ?> × <?= $i['cantidad'] ?></li><?php endforeach; ?>
        </ul>
        <p class="mb-2 small text-muted">
            Total: <strong><?= soles($p['total']) ?></strong> · Pago: <?= esc($p['metodo_pago']) ?>
            <?= $p['direccion'] ? ' · Dirección: ' . esc($p['direccion']) : '' ?>
            <?= $p['telefono'] ? ' · Tel: ' . esc($p['telefono']) : '' ?>
            <?= $p['fecha_entrega'] ? ' · Entregado: ' . esc($p['fecha_entrega']) : '' ?>
        </p>
        <form method="post" action="<?= base_url('admin/pedidos/estado/' . $p['id']) ?>" class="row g-2">
            <?= csrf_field() ?>
            <div class="col-auto">
                <select name="estado" class="form-select form-select-sm">
                    <?php foreach ($permitidos as $e): ?>
                        <option <?= $e === $p['estado'] ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-sm btn-primary">Actualizar estado</button></div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>
