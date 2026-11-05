<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>

<div class="d-flex justify-content-between mb-3">
    <h1 class="h3">Productos</h1>
    <a href="<?= base_url('admin/productos/nuevo') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo producto</a>
</div>

<div class="table-responsive">
<table class="table table-striped align-middle bg-white">
    <thead class="table-dark"><tr><th>#</th><th>Producto</th><th>Categoría</th><th>Marca</th><th class="text-end">Precio</th><th class="text-center">Stock</th><th>Estado</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($productos as $p): $est = estado_stock($p); ?>
        <tr class="<?= $p['activo'] ? '' : 'text-muted' ?>">
            <td><?= $p['id'] ?></td>
            <td><?= esc($p['nombre']) ?></td>
            <td><?= esc($p['categoria']) ?></td>
            <td><?= esc($p['marca'] ?? '-') ?></td>
            <td class="text-end"><?= soles($p['precio']) ?></td>
            <td class="text-center"><span class="badge bg-<?= clase_stock($est) ?>"><?= $p['stock'] ?> · <?= $est ?></span></td>
            <td><?= $p['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' ?></td>
            <td class="text-nowrap">
                <a href="<?= base_url('admin/productos/editar/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <form method="post" action="<?= base_url('admin/productos/estado/' . $p['id']) ?>" class="d-inline"><?= csrf_field() ?>
                    <button class="btn btn-sm btn-outline-<?= $p['activo'] ? 'danger' : 'success' ?>" title="<?= $p['activo'] ? 'Desactivar' : 'Activar' ?>">
                        <i class="bi bi-<?= $p['activo'] ? 'trash' : 'arrow-counterclockwise' ?>"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?= $pager->links() ?>

<?= $this->endSection() ?>
