<form class="card xhr" action="/cuentas/password_actualizar" method="post" target="salida-actualizar">
    <div class="card-header">
        <h5 class="card-header-text">Actualizar contraseña para <span class="text-primary"><?= $cuenta->usuario ?></span></h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col col-sm-6">
                <label for="clave">Nueva contraseña</label>
                <input type="password" id="clave" name="clave" class="form-control" placeholder="Nueva contraseña" />
            </div>
            <div class="form-group col col-sm-6">
                <label for="confirmar">Confirmar contraseña</label>
                <input type="password" id="confirmar" name="confirmar" class="form-control" placeholder="Confirmar contraseña" />
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-sm btn-primary">
            Cambiar contraseña
        </button>
        <span id="salida-actualizar"></span>
    </div>
    <input type="hidden" name="cuenta_id" value="<?= $cuenta->cuenta_id ?>" />
</form>