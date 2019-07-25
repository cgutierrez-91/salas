<div class="card">
    <div class="card-header">
        <h5 class="card-header-text">Explorar Salas</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover table-sm">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Sala</th>
                    <th>Capacidad</th>
                    <th>Proyector</th>
                    <th>Aire acond.</th>
                    
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $salas as $sala ) { ?>
                <tr id="data-row-<?= $sala->sala_id ?>">
                    <td><?= $sala->sala_id ?></td>
                    <td><?= $sala->nombre ?></td>
                    <td><?= $sala->capacidad ?> personas</td>
                    <td><?= $sala->proyector ? 'Sí' : 'No' ?></td>
                    <td><?= $sala->aire ? 'Sí' : 'No' ?></td>
                    
                    <td>
                        <a href="/salas/editar/<?php echo $sala->sala_id; ?>"
                           class="btn btn-sm btn-light xhr"
                           data-toggle="tooltip" data-placement="top"
                           title="Editar">
                            <span class="mdi mdi-pencil"></span>
                        </a>
                        <a href="#eliminar"
                            data-id="<?php echo $sala->sala_id; ?>"
                           class="btn btn-sm btn-light eliminar-sala"
                           data-toggle="tooltip" data-placement="top"
                           data-sala="<?= $sala->nombre ?>"
                           title="Eliminar">
                            <span class="mdi mdi-delete"></span>
                        </a>
                        
                        <span id="salida-eliminar-<?= $sala->sala_id ?>">
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $('.eliminar-sala').click( function(e) {
        e.preventDefault();
        var sala = $(this).data('sala');
        var sala_id = $(this).data('id');
        var salida = 'salida-eliminar-'+sala_id;
        var mensaje = 'Esta acción eliminará la sala "' + sala + '" '
        + 'y la información relacionada a ésta de forma permanente.\n\n'
        + 'IMPORTANTE: Esta acción no se puede revertir.\n\n'
        + '¿Desea continuar?';


        if ( confirm(mensaje) ) {
            beforeAjax();
            var datos = {
                sala: sala_id
            };
            hacerPost('/salas/eliminar', datos, salida);
        }
        
    });
</script>