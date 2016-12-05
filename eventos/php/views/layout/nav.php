<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=<?php url('/') ?>>EventSanLuis</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <?php if( getUrl('/tablero') == $_SERVER['REQUEST_URI']): ?>
                <!-- Solo se escribe cuando esta en la seccion de tablero
                    Por lo tanto no abra conflictos de resolucion con angular -->
                <form class="navbar-form navbar-left" role="search" ng-controller="buscadorCtrl">
                    <div class="form-group">
                        <input type="text" class="form-control" name="busqueda" placeholder="Buscar Evento" ng-model="busqueda" id="buscador">
                    </div>
                    <button type="button" class="btn btn-default" ng-click="buscar()">
                        <span class="glyphicon glyphicon-search"></span>
                        Buscar
                    </button>
                </form>   
            <?php endif; ?>       

            <ul class="nav navbar-nav navbar-right">
                <?php $auth = new Auth(); ?>
                <li><a href=<?php url('/'); ?>>Inicio</a></li>
                <li><a href=<?php url('/tablero'); ?>>Eventos</a></li>
                <?php if(!$auth->isAuth()): ?>
                    <li><a href=<?php url('/login'); ?>>Login</a></li>
                    <li><a href=<?php url('/registro'); ?> >Registro</a></li>
                <?php else: ?>
                    <li><a href=<?php url('/logout') ?>>Logout</a></li>
                <?php endif; ?>            

                <?php if($auth->isAuth()): ?>
                    <li><a href=<?php url('/user/settings') ?>><span class="glyphicon glyphicon-user"></span></a></li>
                <?php endif; ?>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>

