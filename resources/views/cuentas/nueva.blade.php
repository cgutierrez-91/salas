<form class="card xhr" id="cuenta_nueva_form" method="post" action="/cuentas/crear" target="salida-crear">
    <div class="card-header">
        <h5 class="card-header-text">Crear nueva cuenta</h5>
        
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="usuario">Usuario</label>
                <div class="input-group">
                    <input type="text" name="usuario" id="usuario" 
                           class="form-control" 
                           placeholder="Escriba un nombre de usuario" 
                           data-url="/cuentas/disponible"
                           data-target="salida-disponible" />
                    <div class="input-group-append">
                        <span class="input-group-text" 
                              id="salida-disponible"></span>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="email">Correo electrónico</label>
                <div class="input-group">
                    <input type="text" name="email" id="email" 
                           class="form-control" 
                           placeholder="Escriba su correo electrónico" 
                           data-url="/cuentas/disponible-email"
                           data-target="salida-disponible2" />
                    <div class="input-group-append">
                        <span class="input-group-text"
                              id="salida-disponible2"></span>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="clave">Contraseña</label>
                <div class="input-group">
                    <input type="password" name="clave" id="clave" 
                       class="form-control" placeholder="Escriba una contraseña"
                       />
                    <div class="input-group-append">
                        <span class="input-group-text text-primary mdi mdi-information"
                              data-toggle="tooltip"
                              data-placement="top"
                              title="Mín. 6 carácteres."
                              ></span>
                    </div>
                </div>
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="confirmar">Confirmar</label>
                <input type="password" name="confirmar" id="confirmar" 
                       class="form-control" placeholder="Repita la contraseña" 
                       />
            </div>
            <div class="form-group col-12 col-sm-6 col-md-4">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" 
                       class="form-control" placeholder="Nombre completo" />
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary btn-sm pull-right ml-auto">
            <span class="mdi mdi-floppy"></span> Guardar
        </button>
        <span id="salida-crear"></span>
    </div>
</form>

<script>
    $('#usuario,#email').change( function(e){
        
        var params = {
            usuario: this.value
        };
        
        hacerPost('/cuentas/disponible', params, $(this).data('target'));
    });
</script>