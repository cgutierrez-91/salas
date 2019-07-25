<table class="table data-table table-light" height="100%">
    <thead class="thead-light">
        <tr>
            <th colspan="7">
                <div class="btn-group">
                    <a href="/reservar/nueva/<?= $anioAnterior ?>/<?= $mesAnterior ?><?= ($sala_seleccionada!='' ? '/' . $sala_seleccionada : '') ?>" class="btn btn-light xhr">
                        <span class="mdi mdi-chevron-left"></span>
                    </a>
                    <a href="javascript:" class="btn btn-light disabled">
                        <?= $mesTexto ?>, <?= $anio ?>
                    </a>
                    <a href="/reservar/nueva/<?= $anioSiguiente ?>/<?= $mesSiguiente ?><?= ($sala_seleccionada!='' ? '/' . $sala_seleccionada : '') ?>" class="btn btn-light btn-group-text xhr">
                        <span class="mdi mdi-chevron-right"></span>
                    </a>
                </div>
                <span class="d-inline-block">
                    <select class="form-control" id="sala-id">
                        <option value="NULL" class="text-danger">Elija una sala</option>
                        <?php foreach ( $salas as $sala ) { ?>
                        <option value="<?= $sala->sala_id ?>" <?= $sala->sala_id == $sala_seleccionada ? 'selected="selected"' : '' ?>><?= $sala->nombre ?></option>
                        <?php } ?>
                    </select>
                </span>
            </th>
        </tr>
        <tr>
            <?php foreach( $dias as $dia ) { ?>
            <th><?= $dia ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ( $semanas as $semana ) {
            ?><tr><?php
            foreach ( $semana as $dia_semana ) {
                $etiquetas = '';
                
                if ( $sala_seleccionada != '' ){
                
                    if ( session('cuenta')->nivel != 'admin') {

                        $pendientes = DB::table('reservaciones')
                        ->whereBetween('f_uso_desde', ["$anio-$mes-$dia_semana 00:00:00", "$anio-$mes-$dia_semana 23:59:59"])
                        ->where([
                            ['cuenta', session('cuenta')->cuenta_id],
                            ['estado', 'Pendiente'],
                            ['sala', $sala_seleccionada]
                        ])->count();

                        $autorizados = DB::table('reservaciones')
                        ->whereBetween('f_uso_desde', ["$anio-$mes-$dia_semana 00:00:00", "$anio-$mes-$dia_semana 23:59:59"])
                        ->where([
                            ['cuenta', session('cuenta')->cuenta_id],
                            ['estado', 'Autorizado'],
                            ['sala', $sala_seleccionada]
                        ])->count();

                        $rechazados = DB::table('reservaciones')
                        ->whereBetween('f_uso_desde', ["$anio-$mes-$dia_semana 00:00:00", "$anio-$mes-$dia_semana 23:59:59"])
                        ->where([
                            ['cuenta', session('cuenta')->cuenta_id],
                            ['estado', 'Rechazado'],
                            ['sala', $sala_seleccionada]
                        ])->count();
                    }
                    else {
                        $pendientes = DB::table('reservaciones')
                        ->whereBetween('f_uso_desde', ["$anio-$mes-$dia_semana 00:00:00", "$anio-$mes-$dia_semana 23:59:59"])
                        ->where([
                            ['estado', 'Pendiente'],
                            ['sala', $sala_seleccionada]
                        ])->count();

                        $autorizados = DB::table('reservaciones')
                        ->whereBetween('f_uso_desde', ["$anio-$mes-$dia_semana 00:00:00", "$anio-$mes-$dia_semana 23:59:59"])
                        ->where([
                            ['estado', 'Autorizado'],
                            ['sala', $sala_seleccionada]
                        ])->count();

                        $rechazados = DB::table('reservaciones')
                        ->whereBetween('f_uso_desde', ["$anio-$mes-$dia_semana 00:00:00", "$anio-$mes-$dia_semana 23:59:59"])
                        ->where([
                            ['estado', 'Rechazado'],
                            ['sala', $sala_seleccionada]
                        ])->count();
                    }


                    if ( $pendientes>0 ) {
                        $etiquetas .= '<span class="text-secondary mdi mdi-progress-clock"></span>';
                    }

                    if ( $autorizados>0 ) {
                        $etiquetas .= '<span class="text-success mdi mdi-check-circle"></span>';
                    }

                    if ( $rechazados>0 ) {
                        $etiquetas .= '<span class="text-danger mdi mdi-close-circle"></span>';
                    }
                }
                
                ?><td data-href="/reservar/sala/<?= $anio ?>/<?= $mes ?>/<?= $dia_semana ?>"
                    data-dia="<?= $dia_semana ?>"
                    <?php
                    $stamp = mktime(0, 0, 0, $mes, (int)$dia_semana, $anio);
                    $hoy = mktime(0,0,0, date('m'), date('d'), date('Y'));
                    if ($dia_semana>0 && $stamp >= $hoy ) { 
                    ?>
                    class="calendario <?= date('Ymd') == $anio.$mes.$dia_semana ? ' bg-light ' : '' ?>" 
                    ><strong><?= $dia_semana ?></strong><br /><?= $etiquetas ?>
                    <?php } else { ?>
                    class=" text-secondary "><?= $dia_semana ?><br /><?= $etiquetas ?>
                    <?php } ?>
                </td><?php
            }
            ?></tr><?php
        } ?>
    </tbody>
</table>

<script>
    $('table td.calendario').click( function() {
        var anio = <?= $anio ?>;
        var mes = <?= $mes ?>;
        var dia = $(this).data('dia');
        
        var hoy = new Date();
        var fecha = new Date(anio + '-' + mes + '-' + dia + ' 11:59:59');
        if ( hoy.getTime() > fecha.getTime() ){
            alert('No se puede reservar para fechas anteriores');
            return;
        }
        
        if ( $('#sala-id option:selected').val() != 'NULL' ) {
            $('#dialogoTitulo').html('Reservar Sala');
            
            var url = $(this).data('href') + '/' + $('#sala-id option:selected').val();
            hacerGet(url, 'dialogoCuerpo');
            $('#dialogo').modal('show');
        }
        else {
            alert('Debe seleccionar una sala de la lista.');
            $('#sala-id').focus();
        }
    });
    
    $('#sala-id').change( function(e) {
        var url = '<?= '/reservar/nueva/' . $anio . '/' . $mes . '/' ?>' + $(this).val();
        hacerGet(url);
        
    });
</script>