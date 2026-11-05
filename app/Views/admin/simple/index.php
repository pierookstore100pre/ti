<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<?= $this->include('admin/_menu') ?>

<div class="d-flex justify-content-between mb-3">
    <h1 class="h3"><?= esc($titulo) ?></h1>
    <a href="<?= base_url('admin/' . $ruta . '/nuevo') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nueva</a>
</div>

<table class="table table-striped bg-white align-middle">
    <thead class="table-dark"><tr><th>#</th><th>Nombre</th><?php if ($descripcion): ?><th>Descripción</th><?php endif; ?><th>Estado</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($filas as $f): ?>
        <tr>
            <td><?= $f['id'] ?></td>
            <td><?= esc($f['nombre']) ?></td>
            <?php if ($descripcion): ?><td><?= esc($f['descripcion']) ?></td><?php endif; ?>
            <td><?= $f['activo'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>' ?></td>
            <td class="text-nowrap">
                <a href="<?= base_url('admin/' . $ruta . '/editar/' . $f['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <form method="post" action="<?= base_url('admin/' . $ruta . '/estado/' . $f['id']) ?>" class="d-inline"><?= csrf_field() ?>
                    <button class="btn btn-sm btn-outline-<?= $f['activo'] ? 'danger' : 'success' ?>">
                        <i class="bi bi-<?= $f['activo'] ? 'trash' : 'arrow-counterclockwise' ?>"></i>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
