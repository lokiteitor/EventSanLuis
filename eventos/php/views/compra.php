<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirmar compra</title>
    <?php Layout('libs'); ?>
    <script src=<?php asset('/bower_components/angular-cookies/angular-cookies.min.js') ?>></script>
    <script src=<?php asset('/js/ticket.js'); ?>></script>

</head>
<body ng-app="ticketApp">

<?php Layout('nav'); ?>
    <div class="container">
    <h2>Informacion de pago</h2>
    <div class="row" ng-controller="conceptoCtrl">
        <div class="col-xs-10 col-sm-10 col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Concepto</h3>
                </div>
                <div class="panel-body">
                    <h3>{{eventoInfo.TITULO}}</h3>
                    <p><strong>Sala:</strong><?php echo $_COOKIE['n_sala']; ?></p>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tipo de Boleto</th>
                                <th>Cantidad </th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-if="cadultos">
                                <td>Adultos</td>
                                <td>{{cadultos}}</td>
                                <td>${{padultos}}</td>
                            </tr>
                            <tr ng-if="cvejez">
                                <td>Tercera Edad</td>
                                <td>{{cvejez}}</td>
                                <td>${{pvejez}}</td>
                            </tr>
                            <tr ng-if="cestudiantes">
                                <td>Estudiantes</td>
                                <td>{{cestudiantes}}</td>
                                <td>${{pestudiantes}}</td>
                            </tr>
                            <tr ng-if="cninos">
                                <td>Niños</td>
                                <td>{{cninos}}</td>
                                <td>${{pninos}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Total</td>
                                <td>${{total}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>  
        </div>
    </div>


    
    <div class="row" id="main" ng-controller="userCtrl">
    <form action=<?php url('/confirmar/compra') ?> method="POST" class="form-horizontal" role="form">
        <div class=" col-md-4 col-lg-4">
                            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control"  required ng-model="datos.NOMBRE">
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" class="form-control"  required ng-model="datos.APELLIDOS">
            </div>

            <div class="form-group">
                <label for="telefono">Telefono:</label>
                <input type="text" name="telefono" class="form-control"  required ng-model="datos.TELEFONO">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" name="email" class="form-control"  required ng-model="datos.EMAIL">
            </div>            

            <div class="form-group">
                <label for="direccion">Direccion:</label>
                <input type="text" name="direccion" class="form-control"  required ng-model="datos.DIRECCION">
            </div>                

            <div class="form-group hidden-xs hidden-md">                    
                <button type="submit" class="btn btn-danger">Confirmar Compra</button>            
            </div>
    
        </div>
        <div class=" col-md-4 col-lg-4 col-lg-offset-1 col-lg-offset-1">
            <div class="form-group">
                <label for="tarjeta">Numero de Tarjeta</label>
                <input type="text" name="tarjeta" class="form-control"  required>
            </div>
            <div class="form-group">
                <label for="vencimiento">Fecha de vencimiento</label>
                <select name="mes" class="form-control">
                    <option value="0" selected>Mes</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>
            <div class="form-group ">
                <label for="anio">Año</label>
                <select name="anio" class="form-control">
                    <option value="0" selected>Año</option>
                    <?php 
                    for ($i=0; $i < 45; $i++) 
                        echo '<option value='.$i.'>'.$i.'</option>'; 
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="codigo">Codigo de Seguridad</label>
                <input type="number" name="codigo" class="form-control"  min="000" max="999"  required>
            </div>
            <div class="form-group visible-xs visible-md">                    
                <button type="submit" class="btn btn-danger">Confirmar Compra</button>            
            </div>
        </div>
    </form>        
    </div>        
    </div>


</body>
</html>