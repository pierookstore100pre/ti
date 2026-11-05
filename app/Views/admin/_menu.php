<?php
$rol  = $usuario['rol'] ?? '';
$ruta = service('uri')->getSegment(2);
$items = [
    ['',           'Resumen',     'speedometer2', ['SuperAdmin','Administrador','Vendedor','Almacenero','Repartidor']],
    ['productos',  'Productos',   'box-seam',     ['SuperAdmin','Administrador']],
    ['categorias', 'Categorías',  'tags',         ['SuperAdmin','Administrador']],
    ['marcas',     'Marcas',      'award',        ['SuperAdmin','Administrador']],
    ['stock',      'Stock',       'boxes',        ['SuperAdmin','Administrador','Almacenero']],
    ['pedidos',    'Pedidos',     'receipt',      ['SuperAdmin','Administrador','Vendedor','Repartidor']],
];
?>
<ul class="nav nav-pills mb-4 flex-wrap">
    <?php foreach ($items as [$seg, $texto, $icono, $roles]): if (! in_array($rol, $roles, true)) continue; ?>
        <li class="nav-item">
            <a class="nav-link <?= $ruta === $seg || ($seg === '' && $ruta === null) ? 'active' : '' ?>" href="<?= base_url('admin' . ($seg ? '/' . $seg : '')) ?>">
                <i class="bi bi-<?= $icono ?>"></i> <?= $texto ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
