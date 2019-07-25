<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="Reservación de Salas v0.2">
        <meta name="author" content="César Gutierrez">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Reservación de Salas v0.2</title>
        <link rel="stylesheet" href="/libs/bootstrap-4.1.3/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/libs/mdi-2.7.94/css/materialdesignicons.min.css" />
        <link rel="stylesheet" href="/css/sys.css" />
        
        <script src="/libs/jquery-3.3.1/jquery-3.3.1.min.js"></script>
        <script src="/libs/bootstrap-4.1.3/js/bootstrap.bundle.min.js"></script>
        <script src="/js/sys.js"></script>
    </head>
    <body>
        <nav class="navbar fixed-top navbar-primary bg-primary navbar-expand-lg">
            <button id="btnMenuPrincipal" class="btn btn-primary btn-sm">
                <span class="mdi mdi-menu"></span>
            </button>
            
            <!-- div class="collapse navbar-collapse pull-right" -->
                <ul class="navbar-nav pull-right ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" 
                            id="nav-user-menu" role="button" data-toggle="dropdown" 
                            aria-haspopup="true" aria-expanded="false">
                               {{ $usuario }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right " aria-labelledby="nav-user-menu">
                            <a class="dropdown-item xhr" href="/usuario/perfil">
                                Mi perfil
                            </a>
                            <a class="dropdown-item xhr" href="/usuario/perfil/clave">
                                Cambiar contraseña
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item xhr" href="/salir">
                                Salir
                            </a>
                        </div>
                    </li>
                </ul>
            <!-- /div -->
        </nav>
        
        <div id="container-wrapper">
            <nav id="sidebar">
                <?php if ( session('cuenta')->nivel == 'admin' ) { ?>
                <div class="accordion" id="sidebar-accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-block btn-light text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Cuentas
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#sidebar-accordion">
                            <div class="card-body">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action xhr" href="/cuentas"><span class="mdi mdi-account-search"></span> Explorar</a>
                                    <a class="list-group-item list-group-item-action xhr" href="/cuentas/nueva"><span class="mdi mdi-account-plus"></span> Agregar cuenta</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="card">
                        <div class="card-header" id="heading-salas">
                            <h5 class="mb-0">
                                <button class="btn btn-block btn-light text-left" type="button" data-toggle="collapse" data-target="#collapse-salas" aria-expanded="true" aria-controls="collapse-salas">
                                    Salas
                                </button>
                            </h5>
                        </div>

                        <div id="collapse-salas" class="collapse show" aria-labelledby="heading-salas" data-parent="#sidebar-accordion">
                            <div class="card-body">
                                <div class="list-group">
                                    <a class="list-group-item list-group-item-action xhr" href="/salas"><span class="mdi mdi-magnify"></span> Explorar</a>
                                    <a class="list-group-item list-group-item-action xhr" href="/salas/nueva"><span class="mdi mdi-seat-recline-normal"></span> Agregar sala</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header" id="heading-reservaciones">
                        <h5 class="mb-0">
                            <button class="btn btn-block btn-light text-left" type="button" data-toggle="collapse" data-target="#collapse-reservaciones" aria-expanded="true" aria-controls="collapse-reservaciones">
                                Reservaciones
                            </button>
                        </h5>
                    </div>

                    <div id="collapse-reservaciones" class="collapse show" aria-labelledby="heading-reservaciones" data-parent="#sidebar-accordion">
                        <div class="card-body">
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action xhr" href="/reservar"><span class="mdi mdi-magnify"></span> Explorar</a>
                                <a class="list-group-item list-group-item-action xhr" href="/reservar/nueva"><span class="mdi mdi-calendar"></span> Reservar sala</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php } else { ?>
                    <div class="card">
                    <div class="card-header" id="heading-reservaciones">
                        <h5 class="mb-0">
                            <button class="btn btn-block btn-light text-left" type="button" data-toggle="collapse" data-target="#collapse-reservaciones" aria-expanded="true" aria-controls="collapse-reservaciones">
                                Reservaciones
                            </button>
                        </h5>
                    </div>

                    <div id="collapse-reservaciones" class="collapse show" aria-labelledby="heading-reservaciones" data-parent="#sidebar-accordion">
                        <div class="card-body">
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action xhr" href="/reservar"><span class="mdi mdi-calendar-clock"></span> Mis Reservaciones</a>
                                <a class="list-group-item list-group-item-action xhr" href="/reservar/nueva"><span class="mdi mdi-calendar"></span> Reservar sala</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </nav>
            <div id="contenido">
                
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="dialogo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dialogoTitulo">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="dialogoCuerpo">
                      ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        
    </body>
</html>
