<?php
$u      = $usuario ?? null;
$esStaff = $u && in_array($u['rol'], ['SuperAdmin', 'Administrador', 'Vendedor', 'Almacenero', 'Repartidor'], true);
$nCarrito = array_sum(session()->get('carrito') ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($titulo ?? 'Tienda') ?> | Tienda Informática</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-producto img { height: 180px; object-fit: contain; background: #f8f9fa; }
        .card-producto:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15); transition: .2s; }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>"><i class="bi bi-pc-display"></i> Tienda Informática</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('catalogo') ?>">Catálogo</a></li>
                <?php if ($u && ! $esStaff): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('mis-pedidos') ?>">Mis pedidos</a></li>
                <?php endif; ?>
                <?php if ($esStaff): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin') ?>"><i class="bi bi-speedometer2"></i> Panel</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($u): ?>
                    <?php if (! $esStaff): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('carrito') ?>">
                                <i class="bi bi-cart3"></i> Carrito
                                <?php if ($nCarrito): ?><span class="badge bg-warning text-dark"><?= $nCarrito ?></span><?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item"><span class="navbar-text me-2"><i class="bi bi-person-circle"></i> <?= esc($u['nombre']) ?> <small class="text-secondary">(<?= esc($u['rol']) ?>)</small></span></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>">Salir</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('login') ?>">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('registro') ?>">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    <?php if (session()->getFlashdata('exito')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= esc(session()->getFlashdata('exito')) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= esc(session()->getFlashdata('error')) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errores')): ?>
        <div class="alert alert-danger"><ul class="mb-0">
            <?php foreach (session()->getFlashdata('errores') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>

    <?= $this->renderSection('contenido') ?>
</main>

<footer class="bg-dark text-secondary text-center py-3 mt-auto">
    <small>&copy; <?= date('Y') ?> Tienda Informática — Proyecto CodeIgniter 4</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
