<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>

<h1 class="h3 mb-3">Gestión de stock</h1>

<div class="table-responsive">
<table class="table bg-white align-middle">
    <thead class="table-dark"><tr><th>Producto</th><th class="text-center">Stock</th><th>Estado</th><th>Registrar movimiento</th></tr></thead>
    <tbody>
    <?php foreach ($productos as $p): $est = estado_stock($p); $crit = ! $p['a_pedido'] && $p['stock'] <= STOCK_CRITICO; ?>
        <tr class="<?= $crit ? 'table-danger' : '' ?>">
            <td><?= esc($p['nombre']) ?></td>
            <td class="text-center fw-bold"><?= $p['stock'] ?></td>
            <td><span class="badge bg-<?= clase_stock($est) ?>"><?= $est ?></span></td>
            <td>
                <form method="post" action="<?= base_url('admin/stock/ajustar/' . $p['id']) ?>" class="row g-1">
                    <?= csrf_field() ?>
                    <div class="col-auto">
                        <select name="tipo" class="form-select form-select-sm"><option value="entrada">Entrada</option><option value="salida">Salida</option></select>
                    </div>
                    <div class="col-auto"><input type="number" name="cantidad" min="1" value="1" class="form-control form-control-sm" style="width:80px"></div>
                    <div class="col-auto"><button class="btn btn-sm btn-primary">Aplicar</button></div>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?= $this->endSection() ?>
