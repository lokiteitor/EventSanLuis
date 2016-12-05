angular.module('tableroApp', []).
controller('cardsCtrl', ['$scope', '$http','$rootScope', function ($scope,$http,$rootScope) {
    $scope.cadulto = 0;
    $scope.cnino = 0;
    $scope.cvejez = 0;
    $scope.cestudiante = 0;

    $scope.adulto = 0;
    $scope.vejez = 0;
    $scope.nino = 0;
    $scope.estudiante = 0;

    $scope.mapa = false;
    $rootScope.activarBusqueda = false;


    $scope.verMapa = function () {
        $scope.mapa = true;


        $http({
            method:'POST',
            url:$site.root+'/obtener/lugar/evento',
            data:{id:$scope.id}
        }).then(function success(response) {
            $scope.lugar = response.data[0];
            $scope.map = new google.maps.Map(document.getElementById('mapa'), {
              center: {lat: $scope.lugar.LATITUD, lng: $scope.lugar.LONGITUD},
              zoom: 14
            });
            marker = new google.maps.Marker({
                map: $scope.map,
                position: {lat: $scope.lugar.LATITUD, lng: $scope.lugar.LONGITUD},
            });

            $scope.lugar = response.data[0];    
        }, function error(response) {
            alert("Error al conectar con el servidor")
        })
    }

    $scope.ocultarMapa = function () {
        $scope.mapa = false;
    }

    $scope.selsala = function () {
        console.log($scope.sala);
        $scope.nino = ($scope.sala.PRECIO - ($scope.sala.PRECIO*($scope.sala.NINO/100))).toFixed(2);
        $scope.estudiante = ( $scope.sala.PRECIO - ($scope.sala.PRECIO*($scope.sala.ESTUDIANTE/100))).toFixed(2);
        $scope.vejez = ($scope.sala.PRECIO - ($scope.sala.PRECIO*($scope.sala.VEJEZ/100))).toFixed(2);
        $scope.adulto = ($scope.sala.PRECIO - ($scope.sala.PRECIO*($scope.sala.ADULTO/100))).toFixed(2);

        $http({
            method:'POST',
            url:$site.root+'/obtener/ventas',
            data:{id:$scope.id}
        }).then(function success(response) {
            tmp = 0;
            console.log(response.data);

            for (var i = 0; i < response.data.length; i++) {
                if ($scope.sala.N_SALA == response.data[i].N_SALA) {
                    tmp += response.data[i].CANTIDAD;
                }
            }

            $scope.capacidad = $scope.sala.CAPACIDAD - tmp;

        }, function error(response) {
            alert("Error al consultar la base de datos");
        })

    }

    $scope.$watch('cadulto',function (newValue,oldValue) {
        if ($scope.cadulto === undefined){        
            $scope.cadulto = 0;
        }
        $scope.costoadulto = '$'+ ($scope.cadulto * $scope.adulto).toFixed(2);
        $scope.capacidad +=  (oldValue - newValue)
    })
    $scope.$watch('cvejez',function (newValue,oldValue) {
        if ($scope.cvejez === undefined)
            $scope.cvejez = 0;
        $scope.costovejez = '$'+ ($scope.cvejez * $scope.vejez).toFixed(2);
        $scope.capacidad +=  (oldValue - newValue)
    })
    $scope.$watch('cestudiante',function (newValue,oldValue) {
        if ($scope.cestudiante === undefined)
            $scope.cestudiante = 0;
        $scope.costoestudiante = '$'+ ($scope.cestudiante * $scope.estudiante).toFixed(2);
        $scope.capacidad +=  (oldValue - newValue)
    })
    $scope.$watch('cnino',function (newValue,oldValue) {
        if ($scope.cnino === undefined)
            $scope.cnino = 0;
        $scope.costonino = '$'+ ($scope.cnino * $scope.nino).toFixed(2);
        $scope.capacidad +=  (oldValue - newValue)
    })       

    $scope.$watch('capacidad',function (newValue,oldValue) {
        if ($scope.capacidad <= 0) {
            $scope.lleno = true;            
        }
        else{
            $scope.lleno = false;
        }
    })
    $scope.lleno = true;

    $scope.reset = function () {
        $scope.cadulto = 0;
        $scope.cnino = 0;
        $scope.cvejez = 0;
        $scope.cestudiante = 0;
        $scope.mapa = false;
        $scope.adulto = 0;
        $scope.vejez = 0;
        $scope.nino = 0;
        $scope.estudiante = 0;   
        $scope.sala = 0;     
        $scope.id = 0;
        $scope.capacidad = 0;
    }     


    //Esta funcion se ejecuta al abrir el modal y recibe los datos del evento
    // para llenar el modal

    $scope.comprar = function (id) {
        for (var i = 0; i < $rootScope.eventos.length; i++) {
            if (id == $rootScope.eventos[i].ID_EVENTO) {
                $scope.titulo = $rootScope.eventos[i].TITULO;
                $scope.thumb = $rootScope.eventos[i].URL_IMAGEN;
                $scope.descripcion = $rootScope.eventos[i].RESENA;
                $scope.id = id;

                // obtener la info de los boletos
                $http({
                    method:'POST',
                    url:$site.root+'/tablero/obtener/boletos',
                    data:{id:id}
                }).then(function success(response) {
                    // con estos datos se debe de dibujar una tabla con los boletos 
                    // por sala
                    $scope.funciones = response.data;
                }, function error(response) {
                    alert("Error al consultar al servidor");
                });

                break;
            }
        }
    }

    $http({
        method:'GET',
        url:$site.root+'/tablero/obtener/eventos'
    }).then(function success(response) {
        $rootScope.eventos = response.data;
    }, function error(response) {
        alert('Error al conectar al servidor');
    })
}])
.controller('buscadorCtrl', ['$scope', '$http','$rootScope', function ($scope,$http,$rootScope) {
    // controla las busquedas

    $scope.buscar = function () {
        // buscar en el servidor
        $scope.busqueda  = $("#buscador").val();
        $http({
            method:'POST',
            url:$site.root+'/obtener/busqueda',
            data:{titulo:$scope.busqueda}
        }).then(function success(response) {
            console.log(response.data);
            $rootScope.eventos = response.data;
            $rootScope.activarBusqueda = true;
        }, function error(response) {
            alert("Error al consultar al servidor");
        })
    }

}])
.directive('tabCard', [function () {
    return {
        restrict: 'A',
        templateUrl:$site.root+'/templates/tableroCarta.html'
    };
}])
.directive('btComprar', [function () {
    return {
        restrict: 'A',
        scope:{
            // valor del id
            id:'=',
            // referencia a una funcion
            comprar:'&'
        },
        template:'<button type="button" class="btn btn-info" value={{id}} ng-click="comprar({id:id})" data-toggle="modal" data-target="#carrito">Comprar Boletos</button>'
    };
}])



$(document).ready(function() {
    
    $('#buscador').autocomplete({
        source:$site.root+"/buscador"
    })

});
