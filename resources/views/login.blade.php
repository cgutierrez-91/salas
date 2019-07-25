<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="Reservación de Salas v0.1">
        <meta name="author" content="César Gutierrez">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Reservación de Salas v0.2</title>
        <link rel="stylesheet" href="/libs/bootstrap-4.1.3/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/libs/mdi-2.7.94/css/materialdesignicons.min.css" />
        <link rel="stylesheet" href="/css/login.css" />
        
        <script src="/libs/jquery-3.3.1/jquery-3.3.1.min.js"></script>
        <script src="/libs/bootstrap-4.1.3/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container d-flex h-100">
            <form method="post" action="/entrar" class="vertical-align" id="login-form" >
                <h4>Reservación de Sala</h4>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario o email" maxlength="20" />
                    <div class="input-group-append"><span class="input-group-text mdi mdi-account"></span></div>
                </div>
                <div class="input-group input-group-sm">
                    <input type="password" class="form-control" name="clave" id="clave" placeholder="Contraseña" />
                    <div class="input-group-append"><span class="input-group-text mdi mdi-textbox-password"></span></div>
                </div>
                <hr />
                <div id="salida-login"></div>
                <button id="btn-submit" class="btn btn-sm btn-default d-inline-block center-block">
                    Entrar
                </button>
            </form>
        </div>
        
        <script>
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
            $('#login-form').submit( function(e) {
                e.preventDefault();
                $('#salida-login').html('<span class="mdi mdi-spin mdi-loading"></span>');
                $('#btn-submit').prop('disabled', true);
                $.post($(this).attr('action'), $(this).serializeArray())
                .done( function(html) {
                    $('#salida-login').html(html);
                })
                .fail( function(jqXHR, textStatus) {
                    $('#salida-login').html('ERROR: ' + textStatus);
                })
                .always( function() {
                    $('#btn-submit').prop('disabled', false);
                });
            });
        </script>
    </body>
</html>
