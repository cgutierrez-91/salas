<div class="card">
    <div class="card-header">
        <h4>Explorador de Cuentas</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover table-sm">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $cuentas as $cuenta ) { ?>
                <tr id="data-row-<?= $cuenta->cuenta_id ?>">
                    <td><?php echo $cuenta->cuenta_id; ?></td>
                    <td><?php echo $cuenta->usuario; ?></td>
                    <td><?php echo $cuenta->nombre; ?></td>
                    <td><?php echo $cuenta->email; ?></td>
                    <td>
                        <a href="/cuentas/editar/<?php echo $cuenta->cuenta_id; ?>"
                           class="btn btn-sm btn-light xhr"
                           data-toggle="tooltip" data-placement="top"
                           title="Editar">
                            <span class="mdi mdi-account-edit"></span>
                        </a>
                        <a href="#eliminar"
                            data-id="<?php echo $cuenta->cuenta_id; ?>"
                           class="btn btn-sm btn-light eliminar-cuenta"
                           data-toggle="tooltip" data-placement="top"
                           data-usuario="<?= $cuenta->usuario ?>"
                           title="Eliminar">
                            <span class="mdi mdi-account-remove"></span>
                        </a>
                        <a href="/cuentas/password/<?php echo $cuenta->cuenta_id; ?>"
                           class="btn btn-sm btn-light xhr"
                           data-toggle="tooltip" data-placement="top"
                           title="Cambiar contraseña">
                            <span class="mdi mdi-textbox-password"></span>
                        </a>
                        <span id="salida-eliminar-<?= $cuenta->cuenta_id ?>">
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $('.eliminar-cuenta').click( function(e) {
        e.preventDefault();
        var usuario = $(this).data('usuario');
        var cuenta_id = $(this).data('id');
        var salida = 'salida-eliminar-'+cuenta_id;
        var mensaje = 'Esta acción eliminará al usuario "' + usuario + '" '
        + 'y la información relacionada a éste de forma permanente.\n\n'
        + 'IMPORTANTE: Esta acción no se puede revertir.\n\n'
        + '¿Desea continuar?';


        if ( confirm(mensaje) ) {
            beforeAjax();
            var datos = {
                cuenta: cuenta_id
            };
            hacerPost('/cuentas/eliminar', datos, salida);
        }
        
    });
</script>