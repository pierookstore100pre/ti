<?= $this->extend('layout/main') ?>
<?= $this->section('contenido') ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-3">Crear cuenta de cliente</h1>
                <form method="post" action="<?= base_url('registro') ?>" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-md-6">
                        <label class="form-label">Nombres</label>
                        <input name="nombres" value="<?= esc(old('nombres')) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input name="apellidos" value="<?= esc(old('apellidos')) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">DNI / RUC</label>
                        <input name="dni_ruc" value="<?= esc(old('dni_ruc')) ?>" class="form-control" maxlength="11" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono (opcional)</label>
                        <input name="telefono" value="<?= esc(old('telefono')) ?>" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" value="<?= esc(old('email')) ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña (mín. 8 caracteres)</label>
                        <input type="password" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Repetir contraseña</label>
                        <input type="password" name="password_confirm" class="form-control" minlength="8" required>
                    </div>
                    <div class="col-12"><button class="btn btn-primary w-100">Registrarme</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
