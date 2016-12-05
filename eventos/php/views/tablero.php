<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Tablero</title>
        <?php Layout('libs'); ?>
        <script src=<?php asset('/js/user.js') ?>></script>
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtUHYes67iiktLKn-M-RaYnFjMJO6OyMQ">
        </script>
        <script src=<?php asset('/js/libs/jquery-ui.min.js') ?>></script>
        <link rel="stylesheet" href=<?php asset('/css/libs/jquery-ui.theme.min.css') ?>>
        <link rel="stylesheet" href=<?php asset('/css/libs/jquery-ui.structure.min.css') ?>>
        <llibsink rel="stylesheet" href=<?php asset('/css/libs/jquery-ui.min.css') ?>>
    </head>
    <body ng-app="tableroApp" ng-controller="cardsCtrl">
        <?php Layout('nav'); ?>
        <div class="modal fade" id="carrito" tabindex="-1" role="dialog" aria-labelledby="Comprar Boleto">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="tituloevento">{{titulo}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class=" col-md-6 col-lg-6">
                                <p>{{descripcion}}</p>
                                <p><strong>Seleccione la sala</strong></p>
                                <div class="form-group">
                                    <select name="sala" class="form-control" ng-options="x.N_SALA for x in funciones" required ng-model="sala" ng-change="selsala()">
                                    </select>
                                </div>
                                <table class="table table-hover ng-hide" ng-hide="mapa">
                                    <thead>
                                        <tr>
                                            <th>Tipo de boleto</th>
                                            <th>Costo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Niño</td>
                                            <td>{{nino}}</td>
                                        </tr>
                                        <tr>
                                            <td>Estudiante</td>
                                            <td>{{estudiante }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tercera Edad</td>
                                            <td>{{vejez}}</td>
                                        </tr>
                                        <tr>
                                            <td>Adulto</td>
                                            <td>{{adulto}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <address class="ng-hide" ng-show="mapa">
                                    <strong>Direccion:</strong><br>
                                    {{lugar.N_EXT}} {{lugar.CALLE}} <br>
                                    Telefono: {{lugar.TELEFONO}} <br>
                                    Email : {{lugar.EMAIL}} <br>
                                </address>
                                
                                <p><strong>Disponibilidad:</strong><var>{{capacidad}}</var></p>


                            </div>
                            <div class=" col-md-6 col-lg-6">
                                <div class="thumbnail ng-hide" ng-hide="mapa">
                                    <img ng-src="{{thumb}}">
                                </div>
                                
                                <div class="ng-hide" id="mapa" ng-show="mapa"></div>                            
                                <button type="button" class="btn btn-success ng-hide" ng-click="verMapa()" ng-hide="mapa">Ver lugar</button>
                                <button type="button" class="btn btn-success ng-hide" ng-show="mapa" ng-click="ocultarMapa()">Ocultar Mapa</button>
                            </div>
                        </div>
                        <form class="form-inline" role="form" action=<?php url('/comprar') ?> method="POST">
                            <div class="row">
                                <div class="  col-md-8 col-lg-8  text-right">
                                    
                                    <div class="form-group">
                                        <label for="cantidad">Adulto</label>
                                        <input type="number" ng-class="{'has-error':lleno}" min="0" name="cantidadAdulto" class="form-control"  required placeholder="Cantidad" ng-model="cadulto">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="costo" class="form-control"  disabled ng-model="costoadulto" >
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="cantidad">Niño</label>
                                        <input type="number" ng-class="{'has-error':lleno}" min="0" name="cantidadNino" class="form-control"  required placeholder="Cantidad" ng-model="cnino">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="costo" class="form-control"  disabled ng-model="costonino" >
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="cantidad">Estudiante</label>
                                        <input type="number" ng-class="{'has-error':lleno}" min="0" name="cantidadEstudiante" class="form-control"  required placeholder="Cantidad" ng-model="cestudiante">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="costo" class="form-control"  disabled ng-model="costoestudiante" >
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="cantidad">Tercera Edad</label>
                                        <input type="number" ng-class="{'has-error':lleno}" min="0" name="cantidadVejez" class="form-control"  required placeholder="Cantidad" ng-model="cvejez">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="costo" class="form-control"  disabled ng-model="costovejez" >
                                    </div>
                                    <br>
                                    <input type="hidden" name="id_evento" class="form-control" value={{id}}>
                                    <input type="hidden" name="n_sala" id="inputN_sala" class="form-control" value={{sala.N_SALA}}>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="reset()">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row" ng-hide="activarBusqueda">
                <div ng-repeat="evento in eventos">
                    <div class="row" ng-if="$index % 3 == 0">
                        <div tab-card></div>    
                    </div>
                    <div ng-if="$index % 3 != 0" tab-card></div>                        
                </div>
            </div>
            
            <div class="row" ng-show="activarBusqueda">
                <div ng-repeat="evento in eventos">
                    <div tab-card></div>    
                </div>                
            </div>
        

        </div>
    </body>
</html>