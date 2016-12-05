/*Este archivo provee la funcinalidad dinamica al sitio relacionado con la 
    la session del administrador*/

angular.module('dashApp', ['ngRoute'])
.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
    .when('/crear/evento', {
        templateUrl: 'templates/crearEvento.html',
        controller: 'addEventoCtrl'
    })
    .when('/crear/funciones', {
        templateUrl: 'templates/crearFunciones.html',
        controller: 'addFuncionCtrl'
    })
    .when('/crear/lugar', {
        templateUrl: 'templates/crearLugar.html', 
        controller: 'addLugarCtrl'
    })
    .when('/modificar/lugar',{
        templateUrl:'templates/modLugar.html',
        controller: 'modLugarCtrl'
    })
    .when('/registrar/espectacular', {
      templateUrl: 'templates/registrarEspectacular.html',
      controller: 'addEspectacularCtrl'
    })
    .when('/registrar/publicidad', {
      templateUrl: 'templates/registrarPublicidad.html',
      controller: 'addPublicidadCtrl'
    })
    .when('/eliminar/lugar', {
      templateUrl: 'templates/eliminarLugar.html',
      controller: 'rmLugarCtrl'
    })
    .when('/registrar/anuncio',{
      templateUrl: 'templates/registrarAnuncio.html',
      controller: 'addAnuncioCtrl'
    })
    .when('/', {
      templateUrl: 'templates/panel.html',
      controller: 'panelCtrl'
    })
    .when('', {
      templateUrl: 'templates/panel.html',
      controller: 'panelCtrl'
    })    
 
    .otherwise({ redirectTo: '/' });
}]).
controller('addLugarCtrl', ['$scope', function($scope){
    
    $scope.map = new google.maps.Map(document.getElementById('mapa'), {
      center: {lat: 22.151970, lng: -100.975427},
      zoom: 14
    });        
    // este objeto me permite convertir de direcciones a coordenadas
    $scope.geocoder = new google.maps.Geocoder();

    $scope.calle = '';
    $scope.numero = '';
    $scope.site = $site.root;


    $scope.geocodificar = function () {
        var address = $scope.calle + '' + $scope.numero + ' ';
        changeMap($scope.geocoder,$scope.map,address);
    }


}])
.controller('rmLugarCtrl', ['$scope', '$http', function ($scope,$http) {
    // TODO : deprecated
    $scope.lugares = [];
    $scope.site = $site.root;
    
    $scope.map = new google.maps.Map(document.getElementById('mapa'), {
      center: {lat: 22.151970, lng: -100.975427},
      zoom: 14
    });

    // descargar todos los marcadores

    $http({
      method: 'GET',
      url:$site.root+'/obtener/lugares/todos'
    }).then(function success(response) {
      $scope.lugares = response.data;
      for (var i = 0; i < response.data.length; i++) {
        var marker = new google.maps.Marker({
          map: $scope.map,
          position: {lat:parseFloat(response.data[i].LATITUD),lng:parseFloat(response.data[i].LONGITUD)}
        }); 
        
      }

    }, function error(response) {
      alert('Error al conectar con la base de datos');
    })

    $scope.$watch('slugar',function (newValue,oldValue) {
      for (var i = 0; i < $scope.lugares.length; i++) {
        if ($scope.slugar == $scope.lugares[i]) {
          $scope.map.setCenter({lat:parseFloat($scope.lugares[i].LATITUD),lng:parseFloat($scope.lugares[i].LONGITUD)});

          $scope.lugar = $scope.lugares[i];
          break;
        }
      }
    })


}])
.controller('modLugarCtrl', ['$http','$scope', function($http,$scope){
    $scope.geocodificar = function () {
        var address = $scope.calle + '' + $scope.numero + ' ';
        changeMap($scope.geocoder,$scope.map,address);
    }  

    $scope.calle = '';
    $scope.numero = '';
    $scope.site = $site.root;

    $http({
      method:"GET",
      url:$site.root + '/obtener/lugares/todos'
    }).then(function success(response) {
      $scope.ubicaciones = response.data;
      console.log($scope.ubicaciones);
    }, function error(response) {
      alert("Error al conectar con el servidor");
    })

        $scope.map = new google.maps.Map(document.getElementById('mapa'), {
          center: {lat: 22.151970, lng: -100.975427},
          zoom: 14
        });


    $scope.centerMap = function () {;

        $scope.map.setCenter({lat:parseFloat($scope.slugar.LATITUD),lng:parseFloat($scope.slugar.LONGITUD)});
        marker = new google.maps.Marker({
            map: $scope.map,
            position: {lat: parseFloat($scope.slugar.LATITUD), lng: parseFloat($scope.slugar.LONGITUD)},
        });        
    }




}])
.controller('addEventoCtrl', ['$scope','$http', function ($scope,$http) {
    $scope.lugares = [];
    $scope.site = $site.root;
    $scope.map = new google.maps.Map(document.getElementById('mapa'), {
      center: {lat: 22.151970, lng: -100.975427},
      zoom: 12
    });

    $http({
      method: 'GET',
      url:$site.root+'/obtener/lugares/todos'
    }).then(function success(response) {
      $scope.lugares = response.data;
      for (var i = 0; i < response.data.length; i++) {
        var marker = new google.maps.Marker({
          map: $scope.map,
          position: {lat:parseFloat(response.data[i].LATITUD),lng:parseFloat(response.data[i].LONGITUD)}
        }); 
        
      }

    }, function error(response) {
      alert('Error al conectar con la base de datos');
    })

    $scope.$watch('slugar',function (newValue,oldValue) {
      for (var i = 0; i < $scope.lugares.length; i++) {
        if ($scope.slugar == $scope.lugares[i]) {
          $scope.map.setCenter({lat:parseFloat($scope.lugares[i].LATITUD),lng:parseFloat($scope.lugares[i].LONGITUD)});

          $scope.lugar = $scope.lugares[i];
          break;
        }
      }
    })
}])
.controller('addFuncionCtrl', ['$scope','$http', function ($scope,$http) {
  $scope.funciones = [];
  $scope.salas = [];
  $scope.showMod = false;
  $scope.site = $site.root;
  $scope.id = 0;
  // aqui deberiamos de aprovechar este arreglo
  $scope.addF = function () {
    $scope.funciones.push({});    

    setTimeout(200,function () {
      $(".calendar").datetimepicker({
        datepicker:false,
        format:'H:i'
      });      
    })

  }

  $scope.rmF = function () {
    $scope.funciones.pop();
  }

  $scope.showM = function () {
    $scope.showMod = true;
    $scope.idsala = $scope.modfuncion.N_SALA;
  }
  $(document).ready(function() {
    var table = $('#eventosTable').DataTable({
      "ajax":{
        url:$site.root+'/dash/obtener/eventos',
        type:"POST",
      },
      responsive: true,
      select:true,
      "columns":[
        {"data":"TITULO"},
        {"data":'NOMBRE'},
        {"data":"FECHA_INICIO"},
        {"data":"FECHA_FIN"}
      ]
    })

    table
        .on( 'select', function ( e, dt, type, indexes ) {
            var rowData = table.rows( indexes ).data().toArray();
            // Enviar la solicitud con estos datos
            console.log(rowData);
            $scope.id = rowData[0].ID_EVENTO;

            // pedir al servidor las funciones ya registradas para 
            // el evento seleccionado
            $http({
              method:'POST',
              url:$site.root+'/tablero/obtener/boletos',
              data:{id:$scope.id}
            }).then(function success(response) {
              console.log(response.data);
              $scope.salas = response.data;
            }, function error(response) {
              alert("Error al consultar al servidor");
            })

        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            var rowData = table.rows( indexes ).data().toArray();
            $scope.id = '';
            $scope.salas = [];
        } );
  }); 
}])
.controller('addEspectacularCtrl', ['$scope', function ($scope) {
    $scope.site = $site.root;
    $scope.map = new google.maps.Map(document.getElementById('mapa'), {
      center: {lat: 22.151970, lng: -100.975427},
      zoom: 14
    });        
    // este objeto me permite convertir de direcciones a coordenadas
    $scope.geocoder = new google.maps.Geocoder();

    $scope.ubicacion = '';

    $scope.geocodificar = function () {
        changeMap($scope.geocoder,$scope.map,$scope.ubicacion);
    }


}])
.controller('addPublicidadCtrl', ['$scope', function ($scope) {
  $scope.site = $site.root;
  $scope.id = 0;
  // aqui deberiamos de aprovechar este arreglo

  $(document).ready(function() {
    var table = $('#eventosTable').DataTable({
      "ajax":{
        url:$site.root+'/dash/obtener/eventos',
        type:"POST",
      },
      responsive: true,
      select:true,
      "columns":[
        {"data":"TITULO"},
        {"data":'NOMBRE'},
        {"data":"FECHA_INICIO"},
        {"data":"FECHA_FIN"}
      ]
    })

    table
        .on( 'select', function ( e, dt, type, indexes ) {
            var rowData = table.rows( indexes ).data().toArray();
            // Enviar la solicitud con estos datos          
            $scope.id = rowData[0].ID_EVENTO;
            $("input[name='id_evento']").val($scope.id);
            console.log($scope.id);

        } )
        .on( 'deselect', function ( e, dt, type, indexes ) {
            var rowData = table.rows( indexes ).data().toArray();
            $scope.id = '';
        } );
  }); 
  document.getElementById('cartel').addEventListener('change', archivo, false);

}])
.controller('addAnuncioCtrl', ['$http','$scope', function($http,$scope){
  $scope.site = $site.root;
  // mostrar los datos de las selecciones en el formulario
  $scope.slugar = false;
  $scope.cargarAnuncios = function () {
    console.log($scope.sevento.ID_EVENTO)
    $http({
      method:'POST',
      url:$site.root +'/obtener/publicidad/evento',
      data:{id:$scope.sevento.ID_EVENTO}
    }).then(function success(response) {
      console.log(response.data);
      $scope.publicidad = response.data;

    }, function error(response) {
      alert("Error al conectar con el servidor");
    })
  }

  $scope.map = new google.maps.Map(document.getElementById('mapa'), {
    center: {lat: 22.151970, lng: -100.975427},
    zoom: 14
  });       

  $scope.marker = new google.maps.Marker({map:$scope.map});

  $scope.$watch('slugar',function (newValue,oldValue) {
    $scope.map.setCenter({lat:parseFloat($scope.slugar.LATITUD),lng:parseFloat($scope.slugar.LONGITUD)});
    $scope.marker.setPosition({lat:parseFloat($scope.slugar.LATITUD),lng:parseFloat($scope.slugar.LONGITUD)});
  })


  $http({
    method:'GET',
    url:$site.root +'/obtener/eventos/activos'
  }).then(function success(response) {
    $scope.eventos = response.data;
  }, function error(response) {
    alert("Error al conectar con el servidor");
  })

  $http({
    method:'GET',
    url:$site.root +'/obtener/espectaculares'

  }).then(function success(response) {
    $scope.ubicaciones = response.data;
  }, function error(response) {
    alert("Error al conectar con el servidor");
  })




}])
.controller('panelCtrl', ['$scope', '$http', function ($scope,$http) {

  $http({
    method:'GET',
    url:$site.root+'/obtener/eventos/activos'
  }).then(function success(response) {
    $scope.eventos = response.data;
  }, function error(response) {
    alert("Error al conectar con el servidor");
  })

  
  $http({
    method:'GET',
    url:$site.root+'/obtener/eventos/ventas'
  }).then(function success(response) {
    var datos = response.data;
    // dibujar las graficas de mas y menos vendidos
    var conteo = new Array();
    var ctx = document.getElementById("mas-vendidos");

    for (var i = 0; i < Object.keys(datos).length; i++) {
      conteo.push(parseInt(datos[Object.keys(datos)[i]].CANTIDAD));
    }
    console.log(conteo);
    var mvendidos = new Chart(ctx,{
      type:'bar',
      data:{
        labels:Object.keys(datos),
        datasets:[{
          label:"ventas de la semana",
          data:conteo,
          backgroundColor: [
            "#FF6384",
            "#36A2EB",
            "#FFCE56",
            "#ff5722",
            "#9c27b0",
            "#009688",
            
          ],
          borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
          ],          
        }]
      }
    })

  }, function error(response) {
    alert("Error al conectar con el servidor");
  });

  $scope.nsevento = false;
  $scope.dsevento = false;

  $scope.selEvento = function () {
    
    $http({
      method:'POST',
      url:$site.root+'/tablero/obtener/boletos',
      data:{id:$scope.dsevento.ID_EVENTO}
    }).then(function success(response) {
      console.log(response.data);
      $scope.salas = response.data;
    }, function error(response) {
      alert("Error al consultar al servidor");
    })  
  }

  $scope.grafDisp = function() {
    if ($scope.dsevento != false) {
      $http({
        method:'POST',
        url:$site.root + '/obtener/disponibilidad',
        data:{id:$scope.dsevento.ID_EVENTO,n_sala:$scope.nsevento.N_SALA}
      }).then(function success(response) {
        
      var ctx = document.getElementById("disponibilidad");
      var disp = parseInt(response.data.CAPACIDAD - response.data.CANTIDAD);    
      var disponibilidad = new Chart(ctx,{
        type:'pie',
        data:{
          labels:['Disponibles','Vendidos'],
          datasets:[{
            label:"Disponibilidad del evento",
            data:[disp,parseInt(response.data.CANTIDAD)],
            fill:false,
            backgroundColor: [
            "#009688",
            ],
            borderColor: [
            "#009688",
            ],          
          }]
        }
      })


      }, function error(response) {
       alert("Error al conectar con el servidor"); 
      })      
    }
  }


  $scope.grafDia = function () {
    $http({
      method:'POST',
      url:$site.root+'/obtener/ventas/dia',
      data:{id:$scope.sevento.ID_EVENTO}
    }).then(function success(response) {
      console.log(response.data);
      var conteo = new Object();
      var datos = new Array();
      for (var i = 0; i < response.data.length; i++) {
        if (response.data[i].FECHA_VENTA in Object.keys(conteo)) {
          conteo[response.data[i].FECHA_VENTA] += response.data[i].CANTIDAD;
        }
        else{
          conteo[response.data[i].FECHA_VENTA] = response.data[i].CANTIDAD;
        }
      }

      for (var i = 0; i < Object.keys(conteo).length; i++) {
        datos.push(conteo[Object.keys(conteo)[i]]);
      }
      console.log(datos)
      var ctx = document.getElementById("vendidos-semana");

      var dvendidos = new Chart(ctx,{
        type:'line',
        data:{
          labels:Object.keys(conteo),
          datasets:[{
            label:"ventas del evento en la semana",
            data:datos,
            fill:false,
            backgroundColor: [
            "#009688",
            ],
            borderColor: [
            "#009688",
            ],          
          }]
        }
      })
    }, function error(response) {
      alert("Error al conectar con el servidor");
    })    
  }

  $http({
    method:'GET',
    url:$site.root +'/info/edades'
  }).then(function success(response) {

    var conteo = new Array();
    var edades = new Array();
    // quitar los datos con valor 0
    for (var i = 0; i < Object.keys(response.data).length; i++) {
      if (response.data[Object.keys(response.data)[i]] != 0) {
        conteo.push(response.data[Object.keys(response.data)[i]]);
        edades.push(Object.keys(response.data)[i]);
      }
    }

    var ctx = document.getElementById("edades");
    var mvendidos = new Chart(ctx,{
      type:'bar',
      data:{
        labels:edades,
        datasets:[{
          label:"Edades de los usuarios",
          data:conteo,
          backgroundColor: [
            "#FF6384",
            "#36A2EB",
            "#FFCE56",
            "#ff5722",
            "#9c27b0",
            "#009688",
            "#FF6384",
            "#36A2EB",
            "#FFCE56",
            "#ff5722",
            "#9c27b0",
            "#009688",            
          ],
          borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
          ],          
        }]
      }
    })


    
  }, function error(response) {
    alert("Error al conectar con el servidor");
  })



}])
.directive('addFuncion', [function () {
  return {
    restrict: 'A',
    scope:{
      funcion:"@"
    },
    templateUrl:'templates/funcion.html'
  };
}])



function changeMap(geocoder,resultsMap,address) {
    console.log(address);
  geocoder.geocode(
    {
        'address': address,
        componentRestrictions:{
            country:'MX',
            locality:'San Luis Potosi'
        }
    },
   function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      $("input[name='latitud']").val(results[0].geometry.location.lat);
      $("input[name='longitud']").val(results[0].geometry.location.lng);
      console.log(results[0].geometry.location);
      resultsMap.setCenter(results[0].geometry.location);
        // colocar un marcador y observarlo
      marker = new google.maps.Marker({
        map: resultsMap,
        position: results[0].geometry.location
      });

    } else {
      alert('No se pudo encontrar la direccion' + status);
    }
  });
}

// precarga una imagen antes de subirla al servidor
function archivo(evt) {
      var files = evt.target.files; // FileList object
       
        //Obtenemos la imagen del campo "cartel". 
      for (var i = 0, f; f = files[i]; i++) {         
           //Solo admitimos imágenes.
           if (!f.type.match('image.*')) {
                continue;
           }
       
           var reader = new FileReader();
           
           reader.onload = (function(theFile) {
               return function(e) {
               // Creamos la imagen.

                document.getElementById("preview").innerHTML = ['<img class="img-thumbnail" src="', e.target.result,'"/>'].join('');
               };
           })(f);
 
           reader.readAsDataURL(f);
       }
}
