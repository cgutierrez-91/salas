<form class="card xhr" action="/usuario/perfil/clave" method="post" target="salida-actualizar">
    <div class="card-header">
        <h5 class="card-header-text">Cambiar contraseña</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col col-sm-4">
                <label for="actual">Contraseña actual</label>
                <input type="password" id="actual" name="actual" class="form-control" placeholder="Contraseña actual" />
            </div>
            <div class="form-group col col-sm-4">
                <label for="clave">Nueva contraseña</label>
                <input type="password" id="clave" name="clave" class="form-control" placeholder="Nueva contraseña" />
            </div>
            <div class="form-group col col-sm-4">
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
    
</form>