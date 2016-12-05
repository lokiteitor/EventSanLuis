<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrador</title>
    <?php Layout('libs'); ?>
    <link rel="stylesheet" href=<?php asset('/bower_components/metisMenu/dist/metisMenu.min.css'); ?>>
    <script src=<?php asset('/bower_components/metisMenu/dist/metisMenu.min.js'); ?>></script>
    <link rel="stylesheet" href=<?php asset('/css/libs/sb-admin-2.min.css') ?>>
    <script src=<?php asset('/js/libs/sb-admin-2.min.js') ?>></script>
    <script src=<?php asset('/js/app.js') ?>></script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtUHYes67iiktLKn-M-RaYnFjMJO6OyMQ">
    </script>    
    <script src=<?php asset('/bower_components/datatables.net/js/jquery.dataTables.min.js') ?>></script>
    <script src=<?php asset('/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') ?>></script>
    <link rel="stylesheet" href=<?php asset('/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') ?>>

    <script src=<?php asset('/bower_components/datatables.net-select/js/dataTables.select.min.js') ?>></script>    
    <script src=<?php asset('/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js') ?>></script>  
    <script src=<?php asset('/bower_components/datatables.net-responsive/js/dataTables.responsive.min.js') ?>></script> 
    <script src=<?php asset('/bower_components/datatables.net-responsive-bs/js/responsive.bootstrap.js') ?>></script>   
    <script src=<?php asset('/bower_components/chart.js/dist/Chart.bundle.min.js') ?>></script>
    <link rel="stylesheet" href=<?php asset('/bower_components/datatables.net-select-bs/css/select.bootstrap.min.css') ?>>
    <link rel="stylesheet" href=<?php asset('/bower_components/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') ?>>
    <link rel="stylesheet" href=<?php asset('/bower_components/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') ?>>
    
</head>
<body ng-app="dashApp">


<div class="wrapper">

<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>        
            <a class="navbar-brand" href=<?php url('/'); ?>>EventSanLuis</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href=<?php url('/logout') ?>>
                            <span class="glyphicon glyphicon-log-out"></span> Cerrar Sesion
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul id="side-menu" class="nav metismenu">
            <li><a href="#" >Dashboard</a></li>
            <li>
                <a href="#" >Lugares <span class="caret"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="#/crear/lugar">Registrar Lugar</a></li>
                    <!-- <li><a href="#/eliminar/lugar">Eliminar Lugar</a></li> -->
                    <li><a href="#/modificar/lugar">Modificar Lugar</a></li>
                </ul>
            </li>            
            <li>
                <a href="#" >Eventos <span class="caret"></span></a>
                <ul class="nav nav-second-level" >
                    <li><a href="#/crear/evento">Crear Evento</a></li>
                    <li><a href="#/crear/funciones">Crear Funciones</a></li>
                    <!-- <li><a href="#/ver/evento">Monitorear Evento</a></li> -->
                </ul>
            </li>

            <li>
                <a href="#" >Espectaculares <span class="caret"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="#/registrar/espectacular">Registrar Espectacular</a></li>
                    <li><a href="#/registrar/anuncio">Añadir Anuncio</a></li>
<!--                     <li><a href="#">Modificar Anuncio</a></li>
                    <li><a href="#">Eliminar Anuncio</a></li> -->
                </ul>
            </li>
            <li>
                <a href="#">Publicidad <span class="caret"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="#/registrar/publicidad">Crear Publicidad</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>

<div id="page-wrapper">
    <div ng-view></div>
</div>


</div>


</body>
</html>


