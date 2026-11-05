<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>

<h1 class="h3 mb-3"><i class="bi bi-cart3"></i> Mi carrito</h1>

<?php if (! $lineas): ?>
    <div class="alert alert-info">Tu carrito está vacío. <a href="<?= base_url('catalogo') ?>">Ir al catálogo</a></div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-bordered align-middle bg-white">
        <thead class="table-light"><tr><th>Producto</th><th>Estado</th><th class="text-end">Precio</th><th class="text-center">Cant.</th><th class="text-end">Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($lineas as $l): ?>
            <tr>
                <td><?= esc($l['producto']['nombre']) ?></td>
                <td><span class="badge bg-<?= clase_stock($l['estado']) ?>"><?= $l['estado'] ?></span></td>
                <td class="text-end"><?= soles($l['producto']['precio']) ?></td>
                <td class="text-center"><?= $l['cantidad'] ?></td>
                <td class="text-end"><?= soles($l['subtotal']) ?></td>
                <td class="text-center">
                    <form method="post" action="<?= base_url('carrito/quitar/' . $l['producto']['id']) ?>"><?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="4" class="text-end">Total (IGV incluido)</th><th class="text-end fs-5"><?= soles($total) ?></th><th></th></tr>
        </tfoot>
    </table>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <form method="post" action="<?= base_url('carrito/confirmar') ?>" class="card card-body">
            <?= csrf_field() ?>
            <label class="form-label">Método de pago</label>
            <select name="metodo_pago" class="form-select mb-3" required>
                <option value="">Seleccione...</option>
                <option>Yape</option><option>Plin</option><option>Transferencia</option><option>Contra entrega</option>
            </select>
            <button class="btn btn-success"><i class="bi bi-bag-check"></i> Generar pedido</button>
        </form>
    </div>
    <div class="col-md-6">
        <form method="post" action="<?= base_url('carrito/vaciar') ?>"><?= csrf_field() ?>
            <button class="btn btn-outline-secondary">Vaciar carrito</button>
        </form>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
