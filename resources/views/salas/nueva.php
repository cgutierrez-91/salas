<form method="post" class="card xhr" action="/salas/crear" id="salas_form" target="salas_salida">
    <div class="card-header">
        <h5 class="card-header-text">Nueva Sala</h5>
    </div>
    <div class="card-body ">
        <div class="row">
            <div class="form-group col-12 col-sm-6">
                <label for="nombre">Nombre de sala</label>
                <div class="input-group">
                    <input type="text" name="nombre" id="nombre" 
                           class="form-control" 
                           placeholder="Escriba un nombre para la sala" 
                           maxlength="100"
                     />
                </div>
            </div>
            
            <div class="form-group col-12 col-sm-6">
                <label for="capacidad">Capacidad</label>
                <div class="input-group">
                    <input type="number" name="capacidad" id="capacidad" 
                           class="form-control" 
                           placeholder="Capacidad máxima"
                           min="1"
                     />
                    <div class="input-group-append">
                        <span class="input-group-text">Personas</span>
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
                                   class="switch" 
                                   value="on" />
                            <label for="aire"></label>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <label for="proyector">Proyector</label>
                        </td>
                        <td>
                            <input type="checkbox" name="proyector" 
                                   id="proyector" 
                                   class="switch" 
                                   value="on" />
                            <label for="proyector"></label>
                        </td>
                    </tr>
                    
                    
                </table>
            </div>
            
            <div class="form-group col-12 col-sm-6">
                <label for="otros">Otros equipos</label>
                <textarea name="otros" id="otros" rows="5" class="form-control"></textarea>
            </div>
            
        </div>
    </div>
    <div class="card-footer">
        <button class="btn btn-sm btn-primary">Guardar</button>
        <span id="salas_salida"></span>
    </div>
</form>