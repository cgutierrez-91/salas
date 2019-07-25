<form method="post" class="card xhr" action="/salas/crear" id="salas_form" target="salas_salida">
    <div class="card-header">
        <h5 class="card-header-text">Editar Sala</h5>
    </div>
    <div class="card-body ">
        <div class="row">
            <div class="form-group col-12 col-sm-6">
                <label for="nombre">Nombre de sala</label>
                <div class="input-group">
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control auto-update" 
                           placeholder="Escriba un nombre para la sala" 
                           maxlength="100"
                           data-salida="salida-nombre"
                           value="<?= $sala->nombre ?>"
                     />
                    <div class="input-group-append">
                        <span class="input-group-text"
                              id="salida-nombre"
                              ></span>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-12 col-sm-6">
                <label for="capacidad">Capacidad</label>
                <div class="input-group">
                    <input type="number" name="capacidad" id="capacidad" 
                           class="form-control auto-update" 
                           placeholder="Capacidad máxima"
                           min="1"
                           data-salida="salida-capacidad"
                           value="<?= $sala->capacidad ?>"
                     />
                    <div class="input-group-append">
                        <span class="input-group-text">Personas</span>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text" id="salida-capacidad"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            
            
            <div class="form-group col-12 col-sm-6">
                <table class="table table-condensed">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="2" >Equipo disponible</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>
                            <label for="aire">Aire acondicionado</label>
                        </td>
                        <td>
                            <input type="checkbox" name="aire" id="aire" 
                                   class="switch auto-update" 
                                   value="on"
                                   data-salida="salida-aire"
                                   <?= $sala->aire ? ' checked ' : '' ?>
                                   />
                            <label for="aire" id="salida-aire"></label>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <label for="proyector">Proyector</label>
                        </td>
                        <td>
                            <input type="checkbox" name="proyector" 
                                   id="proyector" 
                                   class="switch auto-update" 
                                   value="on"
                                   data-salida="salida-proyector"
                                   <?= $sala->proyector ? ' checked ' : '' ?>
                                   />
                            <label for="proyector" id="salida-proyector"></label>
                        </td>
                    </tr>
                    
                    
                </table>
            </div>
            
            <div class="form-group col-12 col-sm-6">
                <label for="otros">Otros equipos</label>
                <span id="salida-otros"></span>
                <textarea name="otros" id="otros" rows="5" 
                          class="form-control auto-update"
                          data-salida="salida-otros"
                          ><?= $sala->otros ?></textarea>
            </div>
            
        </div>
    </div>
    
</form>

<script>
    $('.auto-update').change( function() {
        var datos = {
            campo: this.name,
            valor: this.value,
            checked: this.checked ? true : false,
            id : '<?= $sala->sala_id ?>'
        };
        
        hacerPost('/salas/actualizar', datos, $(this).data('salida'));
    });
</script>