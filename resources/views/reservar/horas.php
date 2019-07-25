<form action="/reservar/sala" method="post" class="row xhr" target="salida-horas">
    <div class="col col-sm-6">
        <label for="desde">
            Desde
        </label>    
            
        <select name="desde" id="desde" class="form-control">
        <?php foreach( $desde as $valor => $hora ) {
        $reservado = DB::table('reservaciones')
        ->whereRaw("'$fecha $valor' BETWEEN f_uso_desde AND f_uso_hasta")
        ->where([
            ['sala', $sala]
        ])
        ->count();
            ?>
            <option value="<?= $valor ?>" <?= ($reservado>0 ? ' disabled="disabled" ' : '') ?>><?= $hora ?></option>
        <?php } ?>
        </select>
        
    </div>
    
    
    <div class="col col-sm-6 form-group">
        <label for="hasta">
            Hasta
        </label>    
            
        <select name="hasta" id="hasta" class="form-control">
        <?php foreach( $hasta as $valor => $hora ) { 
        $reservado = DB::table('reservaciones')
        ->whereRaw("'$fecha $valor' BETWEEN f_uso_desde AND f_uso_hasta")
        ->where([
            ['sala', $sala]
        ])
        ->count();
        ?>
            <option value="<?= $valor ?>" <?= ($reservado>0 ? ' disabled="disabled" ' : '') ?>><?= $hora ?></option>
        <?php } ?>
        </select>
        
    </div>
    <input type="hidden" name="fecha" value="<?= $fecha ?>" />
    <input type="hidden" name="sala" value="<?= $sala ?>" />
    <div id="salida-horas" class="alert"></div>
</form>