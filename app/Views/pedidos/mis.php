<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>

<h1 class="h3 mb-3">Mis pedidos</h1>

<?php if (! $pedidos): ?>
    <div class="alert alert-info">Aún no has realizado pedidos.</div>
<?php endif; ?>

<?php foreach ($pedidos as $p): ?>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Pedido #<?= $p['id'] ?></strong>
            <span class="badge bg-<?= clase_estado_pedido($p['estado']) ?>"><?= esc($p['estado']) ?></span>
        </div>
        <div class="card-body">
            <p class="mb-2 text-muted small">
                <?= esc($p['fecha_venta']) ?> · Pago: <?= esc($p['metodo_pago']) ?>
                <?= $p['fecha_entrega'] ? ' · Entregado: ' . esc($p['fecha_entrega']) : '' ?>
            </p>
            <ul class="mb-2">
                <?php foreach ($p['items'] as $i): ?>
                    <li><?= esc($i['nombre']) ?> × <?= $i['cantidad'] ?> — <?= soles($i['subtotal']) ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="text-end">
                <small class="text-muted">Subtotal <?= soles($p['subtotal']) ?> + IGV <?= soles($p['igv']) ?></small><br>
                <strong>Total: <?= soles($p['total']) ?></strong>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
