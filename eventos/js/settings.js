angular.module('settingsApp', [])
.controller('setupCtrl', ['$scope', '$http', function ($scope,$http) {
    

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