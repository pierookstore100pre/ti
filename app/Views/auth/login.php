<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Iniciar sesión</h1>
                <form method="post" action="<?= base_url('login') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" value="<?= esc(old('email')) ?>" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Ingresar</button>
                </form>
                <p class="mt-3 mb-0 text-center">¿No tienes cuenta? <a href="<?= base_url('registro') ?>">Regístrate</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
