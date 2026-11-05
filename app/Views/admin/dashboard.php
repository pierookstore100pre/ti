<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>

<h1 class="h3 mb-3">Panel de administración</h1>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card text-bg-primary"><div class="card-body"><div class="fs-2"><?= $totalProductos ?></div>Productos activos</div></div></div>
    <div class="col-md-4"><div class="card text-bg-secondary"><div class="card-body"><div class="fs-2"><?= $pendientes ?></div>Pedidos pendientes</div></div></div>
    <div class="col-md-4"><div class="card text-bg-info"><div class="card-body"><div class="fs-2"><?= $porEntregar ?></div>Pedidos por entregar</div></div></div>
</div>

<?php if (in_array($usuario['rol'], ['SuperAdmin', 'Administrador', 'Almacenero'], true)): ?>
<div class="card border-danger">
    <div class="card-header bg-danger text-white"><i class="bi bi-exclamation-triangle-fill"></i> Stock Crítico (<?= STOCK_CRITICO ?> unidades o menos)</div>
    <?php if (! $criticos): ?>
        <div class="card-body text-success">Todo el inventario está en niveles normales.</div>
    <?php else: ?>
        <table class="table table-sm mb-0">
            <thead><tr><th>Producto</th><th class="text-end">Stock</th></tr></thead>
            <tbody>
            <?php foreach ($criticos as $c): ?>
                <tr class="table-danger"><td><?= esc($c['nombre']) ?></td><td class="text-end fw-bold"><?= $c['stock'] ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
