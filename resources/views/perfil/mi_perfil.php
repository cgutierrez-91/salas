<div class="card">
    <div class="card-header">
        <h5 class="card-header-text">Mi Perfil</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="usuario">Usuario</label>
                <div class="input-group">
                    <input type="text" name="usuario" id="usuario"
                           placeholder="Usuario" class="form-control auto-update"
                           value="<?= $usuario->usuario ?>"
                           data-salida="salida-usuario" />
                    <div class="input-group-append">
                        <span class="input-group-text" id="salida-usuario">
                            <span class="mdi mdi-checkbox-blank-circle-outline text-muted">   
                            </span>
                        </span>
                    </div>
                </div>
            </div>


            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="email">Email</label>
                <div class="input-group">
                    <input type="text" name="email" id="email"
                           placeholder="Correo electrónico" class="form-control auto-update"
                           value="<?= $usuario->email ?>"
                           data-salida="salida-email" />
                    <div class="input-group-append">
                        <span class="input-group-text" id="salida-email">
                            <span class="mdi mdi-checkbox-blank-circle-outline text-muted">   
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="nombre">Nombre completo</label>
                <div class="input-group">
                    <input type="text" name="nombre" id="nombre"
                           placeholder="Escriba el nombre completo" class="form-control auto-update"
                           value="<?= $usuario->nombre ?>"
                           data-salida="salida-nombre" />
                    <div class="input-group-append">
                        <span class="input-group-text" id="salida-nombre">
                            <span class="mdi mdi-checkbox-blank-circle-outline text-muted">   
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</div>
<script>
    $('.auto-update').change( function() {
        var datos = {
            campo: this.name,
            valor: this.value,
            id : '<?= $usuario->cuenta_id ?>'
        };
        
        hacerPost('/usuario/perfil/actualizar', datos, $(this).data('salida'));
    });
</script>