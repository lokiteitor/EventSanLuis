angular.module('ticketApp', ['ngCookies'])
.controller('conceptoCtrl', ['$scope', '$http','$cookies', function ($scope,$http,$cookies) {
    $http({
        method:'GET',
        url:$site.root+'/obtener/info/evento'        
    }).then(function success(response) {
        $scope.eventoInfo = response.data[0];
        var precio = $scope.eventoInfo.PRECIO
        $scope.total = 0;
        if ($cookies.get('adultos') != 0) {
            $scope.cadultos = $cookies.get('adultos');
            $scope.padultos = parseInt($cookies.get('adultos')) * parseInt(precio - (precio * $scope.eventoInfo.ADULTO/100));
            $scope.total += $scope.padultos;
        }
        else
            $scope.cadultos = false;
        if ($cookies.get('ancianos') != 0) {
            $scope.cvejez = $cookies.get('ancianos');
            $scope.pvejez = parseInt($cookies.get('ancianos')) * parseInt(precio - (precio * $scope.eventoInfo.VEJEZ/100));
            $scope.total += $scope.pvejez;
        }
        else
            $scope.cvejez = false;
        if ($cookies.get('estudiantes') != 0) {
            $scope.cestudiantes = $cookies.get('estudiantes');
            $scope.pestudiantes = parseInt($cookies.get('estudiantes')) * parseInt(precio - (precio * $scope.eventoInfo.ESTUDIANTE/100));
            $scope.total += $scope.pestudiantes;
        }
        else
            $scope.cestudiantes = false;

        if ($cookies.get('ninos') != 0) {
            $scope.cninos = $cookies.get('ninos');
            $scope.pninos = parseInt($cookies.get('ninos')) * parseInt(precio - (precio * $scope.eventoInfo.NINO/100));
            $scope.total += $scope.pninos;
        }
        else
            $scope.cninos = false;


    }, function error(response) {
        alert("Error al consultar la base de datos");
    })
}])
.controller('userCtrl', ['$scope', '$http', function ($scope,$http) {
    $http({
        method:'GET',
        url:$site.root+'/obtener/info/user'
    }).then(function success(response) {
        console.log(response.data);
        $scope.datos = response.data[0];
    }, function (response) {
        alert("Error al consultar al servidor");
    })    
}])