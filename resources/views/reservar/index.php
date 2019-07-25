<form class="card xhr" method="post" action="/reservar/" id="form-reservaciones">
    <div class="card-header">
        <h5 class="card-header-text">
            Reservaciones
            <?= $nombre_sala != '' ? ' para <span class="text-primary">' . $nombre_sala . '</span>' : '' ?>
            <?= $nombre_solicitante != '' ? ' por <span class="text-primary">' . $nombre_solicitante . '</span>' : '' ?>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="filtro-sala">Sala</label>
                    <select name="filtro-sala" id="filtro-sala" class="form-control">
                        <option value="TODAS">Todas las salas</option>
                        <?php foreach ($salas as $sala ) { ?>
                        <option value="<?= $sala->sala_id ?>" <?= $sala->sala_id == $sala_actual ? ' selected ' : '' ?>><?= $sala->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        
            <?php if ( session('cuenta')->nivel == 'admin' ) { ?>

            <div class="col col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="filtro-solicitante">Solicitante</label>
                    <select name="filtro-solicitante" id="filtro-solicitante" class="form-control">
                        <option value="TODOS">Todos los solicitantes</option>
                        <?php foreach ($cuentas as $cuenta ) { ?>
                        <option value="<?= $cuenta->cuenta_id ?>" <?= $cuenta->cuenta_id == $solicitante_actual ? ' selected ' : '' ?>><?= $cuenta->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php } ?>
            
            <div class="col col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="filtro-estado">Estado</label>
                    <select name="filtro-estado" id="filtro-estado" class="form-control">
                        <option value="TODOS">Todos los estados</option>
                        <option value="Pendiente" <?= $estado_actual == 'Pendiente' ? ' selected ' : '' ?>>Pendientes</option>
                        <option value="Autorizado" <?= $estado_actual == 'Autorizado' ? ' selected ' : '' ?>>Autorizadas</option>
                        <option value="Rechazada" <?= $estado_actual == 'Rechazada' ? ' selected ' : '' ?>>Rechazadas</option>
                        <option value="Cancelada" <?= $estado_actual == 'Cancelada' ? ' selected ' : '' ?>>Canceladas</option>
                    </select>
                </div>
            </div>
            
            
        </div>
        
        <table class="table table-striped table-condensed table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Sala</th>
                    <th>Solicitante</th>
                    <th>F. Reservación</th>
                    <th>Hora de Uso</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ( $reservaciones as $reservacion ) { 
                $f_reserva = new DateTime($reservacion->f_reserva);    
                $f_desde = new DateTime($reservacion->f_uso_desde);    
                $f_hasta = new DateTime($reservacion->f_uso_hasta);
                $f_hasta->add(new DateInterval('PT1S'));
                ?>
                <tr id="data-row-<?= $reservacion->reservacion_id ?>">
                    <td><?= $reservacion->sala_nombre ?></td>
                    <td><?= $reservacion->solicitante ?></td>
                    <td><?= $f_reserva->format('d/m/Y h:iA') ?></td>
                    <td>
                        <?= $f_desde->format('d/m/Y') ?>
                        <span class="badge badge-info">
                        <?= $f_desde->format('h:iA') ?>
                        -
                        <?= $f_hasta->format('h:iA') ?>
                        </span>
                    </td>
                    <td><span class="badge badge-<?php 
                        switch ( $reservacion->estado ) {
                            case 'Pendiente': print('secondary'); break;
                            case 'Rechazado': print('danger'); break;
                            case 'Autorizado': print('success'); break;
                            case 'Cancelado': print('warning'); break;
                        } ?>
                        "><?= $reservacion->estado ?></span>
                    </td>
                    <td>
                    <?php if ( session('cuenta')->nivel == 'admin' ) { ?>
                        <a href="/reservar/eliminar/<?= $reservacion->reservacion_id ?>" 
                           class="btn btn-danger btn-sm eliminar-reservacion"
                           title="Eliminar" 
                           data-toggle="tooltip" 
                           data-id="<?= $reservacion->reservacion_id ?>"
                           target="salida-eliminar-<?= $reservacion->reservacion_id ?>"
                           data-placement="top">
                            <span class="mdi mdi-delete-circle"></span>
                            <span id="salida-eliminar-<?= $reservacion->reservacion_id ?>"></span>
                        </a>
                        
                        <?php if ( $reservacion->estado == 'Pendiente' ) { ?>
                        <a href="/reservar/autorizar/<?= $reservacion->reservacion_id ?>"
                           class="btn btn-success btn-sm btn-autorizar post"
                           target="autorizar-status-<?= $reservacion->reservacion_id ?>"
                           data-id="<?= $reservacion->reservacion_id ?>"
                           title="Autorizar"
                           data-toggle="tooltip"
                           data-placement="top">
                            <span class=" mdi mdi-check-circle"></span>
                            <span id="autorizar-status-<?= $reservacion->reservacion_id ?>"></span>
                        </a>
                        
                        <a href="/reservar/rechazar/<?= $reservacion->reservacion_id ?>"
                           class="btn btn-warning btn-sm post"
                           data-id="<?= $reservacion->reservacion_id ?>"
                           title="Rechazar"
                           data-toggle="tooltip"
                           target="rechazar-status-<?= $reservacion->reservacion_id ?>"
                           data-placement="top">
                            <span class=" mdi mdi-block-helper text-light"></span>
                            <span id="rechazar-status-<?= $reservacion->reservacion_id ?>"></span>
                        </a>
                        <input type="text" id="comentario<?= $reservacion->reservacion_id ?>" class="form-control" placeholder="Escriba un comentario" />
                        <?php } ?>
                    <?php } elseif($f_desde->getTimestamp() >= time() && $reservacion->estado!='Cancelado') { ?>
                        <a href="/reservar/cancelar/<?= $reservacion->reservacion_id ?>"
                           class="btn btn-secondary btn-sm xhr"
                           target="cancelar-status-<?= $reservacion->reservacion_id ?>"
                           data-placement="top">
                            Cancelar
                            <span id="cancelar-status-<?= $reservacion->reservacion_id ?>"></span>
                        </a>
                    <?php } ?>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <nav>
            <ul class="pagination">
                <?php
                $padding = 3;
                $paginas = ceil($total / $limite);
                for($pagina = $actual - $padding; $pagina < $actual; $pagina++) {
                    if ( $pagina >= 1 ) {
                    ?>
                <li class="page-item"><a class="page-link page-link-valido" data-pagina="<?= $pagina ?>" href="#"><?= $pagina ?></a></li>
                    <?php
                    }
                }
                ?>
                <li class="page-item active"><a class="page-link" ><?= $actual ?></a></li>
                <?php
                for($pagina = $actual + 1; $pagina <= $actual+$padding; $pagina++) {
                    if ( $pagina <= $paginas ){
                    ?>
                <li class="page-item"><a class="page-link page-link-valido" data-pagina="<?= $pagina ?>" href="#"><?= $pagina ?></a></li>
                    <?php
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
    <input type="hidden" id="pagina-actual" name="actual" value="<?= $actual ?>" />
</form>

<script>
    $('.page-link-valido').click( function(e) {
        e.preventDefault();
        $('#pagina-actual').val($(this).data('pagina'));
        $('#form-reservaciones').submit();
    });
    
    $('#form-reservaciones select').change(function(e){
        $('#pagina-actual').val(1);
        $('#form-reservaciones').submit();
    });

    $('.eliminar-reservacion').click( function(e) {
        e.preventDefault();
        
        var reservacion_id = $(this).data('id');
        var salida = 'salida-eliminar-'+reservacion_id;
        var mensaje = 'Esta acción eliminará la reservación de forma permanente.\n\n'
        + 'IMPORTANTE: Esta acción no se puede revertir.\n\n'
        + '¿Desea continuar?';


        if ( confirm(mensaje) ) {
            beforeAjax();
            var datos = {
                reservacion: reservacion_id
            };
            hacerPost('/reservar/eliminar', datos, salida);
        }
        
    });
    
    $('a.post').click( function(e){
        e.preventDefault();
        var id_solicitud = $(this).data('id');
        var salida = $(this).attr('target');
        var params = {'comentario': $('#comentario'+id_solicitud).val()};
        hacerPost($(this).attr('href'), params, salida);
    });
</script>