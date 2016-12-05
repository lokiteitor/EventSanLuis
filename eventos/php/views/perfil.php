<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Configuracion del perfil</title>
        <?php Layout('libs') ?>
        <script src=<?php asset('/js/settings.js') ?>></script>
    </head>
    <body ng-app="settingsApp">
        <?php Layout('nav') ?>
        <div class="container" id="main">
            <h2>Configuracion de perfil</h2>
            
            <form action=<?php url('/user/settings') ?> method="POST" class="form-horizontal" role="form" ng-controller="setupCtrl">
                <div class="row">
                    <div class="col-md-5 col-lg-5 col-xs-10 col-xs-offset-1">
                        <h4>Informacion personal</h4>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control" ng-model='datos.NOMBRE'>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellidos</label>
                            <input type="text" name="apellido" class="form-control" ng-model="datos.APELLIDOS">
                        </div>
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select name="sexo" class="form-control" ng-model="datos.SEXO">
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nacimiento">Fecha de Nacimiento</label>
                            <input type="text" name="nacimiento" class="form-control" ng-model="datos.FECHA_NAC">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary hidden-sm hidden-xs">Guardar</button>
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-5 col-lg-offset-1 col-md-offset-1 col-xs-10 col-xs-offset-1">
                        <h4>Informacion de contacto</h4>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" ng-model="datos.EMAIL">
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Telefono</label>
                            <input type="text" name="telefono" class="form-control" ng-model="datos.TELEFONO">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Direccion</label>
                            <input type="text" name="direccion" class="form-control" ng-model="datos.DIRECCION">
                        </div>
                        <div class="form-group">
                            <label for="rfc">RFC</label>
                            <input type="text" name="rfc" class="form-control" ng-model="datos.RFC" >
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary show-sm show-xs">Guardar</button>
                        </div>                        
                    </div>
                </div>
            </form>
            <form action=<?php url("/cambiar/password") ?> method="POST" class="form-horizontal" role="form">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-lg-offset-6 col-md-offset-2">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title">Cambiar Contraseña</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-6 col-lg-6 ">
                                    <div class="form-group">
                                        <label for="oldpass">Password Actual</label>
                                        <input type="password" name="oldpass" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="newpass">Nuevo Password</label>
                                        <input type="password" name="newpass" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" class="btn btn-danger">Cambiar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>

<script>
    $.datetimepicker.setLocale('es');

    $("input[name='nacimiento']").datetimepicker({
     i18n:{
      es:{
       months:[
        'Enero','Febrero','Marzo','Abril',
        'Mayo','Junio','Julio','Agosto',
        'Septiembre','Octubre','Noviembre','Diciembre',
       ],
       dayOfWeek:[
        "Domi", "Lun", "Mart", "Mier", 
        "Juev", "Vier", "Saba",
       ]
      }
     },
     timepicker:false,
     format:'Y-m-d'
    });
    
</script>

</html>
