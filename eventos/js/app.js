/**
 * @author David Delgado <lokiteitor513@gmail.com>
 * @file Este archivo contiene toda la aplicacion angular enfocada 
 * a la parte del dashboard del administrador
 * @licence MIT
 * 
 */

/**
 * aplicacion del dashboard, aqui se definen los enrutadores 
 * para cada accion en el dashboard
 */
angular.module('dashApp', ['ngRoute'])
  .config(['$routeProvider', function($routeProvider) {
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
      .when('/modificar/lugar', {
        templateUrl: 'templates/modLugar.html',
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
      .when('/registrar/anuncio', {
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

    .otherwise({
      redirectTo: '/'
    });
  }])
  .controller('addLugarCtrl', ['$scope', function($scope) {
    /**
     * controlador para la funcion agregar evento
     */
    // crear un mapa
    $scope.map = getCenterMap();
    // este objeto me permite convertir de direcciones a coordenadas
    $scope.geocoder = new google.maps.Geocoder();

    $scope.calle = '';
    $scope.numero = '';
    $scope.site = $site.root;

    // crea un geocodificador de google maps
    $scope.geocodificar = function() {
      var address = $scope.calle + '' + $scope.numero + ' ';
      changeMap($scope.geocoder, $scope.map, address);
    }


  }])
  .controller('modLugarCtrl', ['$http', '$scope', function($http, $scope) {
    /**
     * Controlador para la funcion modificar lugar
     */
    $scope.geocoder = new google.maps.Geocoder();
    $scope.geocodificar = function() {
      var address = $scope.slugar.CALLE + '' + $scope.slugar.N_EXT + ' ';
      changeMap($scope.geocoder, $scope.map, address);
    }

    $scope.calle = '';
    $scope.numero = '';
    $scope.site = $site.root;
    // obtener todos los lugares para presentar disponibles
    $http({
      method: "GET",
      url: $site.root + '/obtener/lugares/todos'
    }).then(function success(response) {
      $scope.ubicaciones = response.data;
      console.log($scope.ubicaciones);
    }, function error(response) {
      alert("Error al conectar con el servidor");
    })

    $scope.map = getCenterMap();

    // centrar el mapa a la ubicacion seleccionada
    $scope.centerMap = function() {;

      $scope.map.setCenter({
        lat: parseFloat($scope.slugar.LATITUD),
        lng: parseFloat($scope.slugar.LONGITUD)
      });
      marker = new google.maps.Marker({
        map: $scope.map,
        position: {
          lat: parseFloat($scope.slugar.LATITUD),
          lng: parseFloat($scope.slugar.LONGITUD)
        },
      });
    }



  }])
  .controller('addEventoCtrl', ['$scope', '$http', function($scope, $http) {
    /**
     * contralador para la funcion de agregar evento
     */
    $scope.lugares = [];
    $scope.site = $site.root;
    $scope.map = getCenterMap();
    // obtener todos los lugares para presentar disponibles 
    // y agreagar un marcador
    $http({
      method: 'GET',
      url: $site.root + '/obtener/lugares/todos'
    }).then(function success(response) {
      $scope.lugares = response.data;
      for (var i = 0; i < response.data.length; i++) {
        var marker = new google.maps.Marker({
          map: $scope.map,
          position: {
            lat: parseFloat(response.data[i].LATITUD),
            lng: parseFloat(response.data[i].LONGITUD)
          }
        });

      }

    }, function error(response) {
      alert('Error al conectar con la base de datos');
    })

    // cuando la seleccion de lugar cambie centrar nuevamente el mapa
    $scope.$watch('slugar', function(newValue, oldValue) {
      for (var i = 0; i < $scope.lugares.length; i++) {
        if ($scope.slugar == $scope.lugares[i]) {
          $scope.map.setCenter({
            lat: parseFloat($scope.lugares[i].LATITUD),
            lng: parseFloat($scope.lugares[i].LONGITUD)
          });

          $scope.lugar = $scope.lugares[i];
          break;
        }
      }
    })
  }])
  .controller('addFuncionCtrl', ['$scope', '$http', function($scope, $http) {
    /**
     * contralador para la funcion agregar funcion
     */
    $scope.funciones = [];
    $scope.salas = [];
    $scope.showMod = false;
    $scope.site = $site.root;
    $scope.id = 0;
    // agregar una funcion
    $scope.addF = function() {
      $scope.funciones.push({});
    }
    // remover una funcion
    $scope.rmF = function() {
      $scope.funciones.pop();
    }
    // mostrar los daots de una funcion a modificar
    $scope.showM = function() {
      $scope.showMod = true;
      $scope.idsala = $scope.modfuncion.N_SALA;
    }

    $(document).ready(function() {
      // esta parte crea una tabla atraves de los datos que recive via ajax
      // es de tipo responsivo y con la capacidad de seleccionar un registro
      var table = $('#eventosTable').DataTable({
        "ajax": {
          url: $site.root + '/dash/obtener/eventos',
          type: "POST",
        },
        responsive: true,
        select: true,
        "columns": [{
          "data": "TITULO"
        }, {
          "data": 'NOMBRE'
        }, {
          "data": "FECHA_INICIO"
        }, {
          "data": "FECHA_FIN"
        }]
      })

      table
        // al seleccionar un dato requerir al servidor la informacion sobre ese
        // registro
        .on('select', function(e, dt, type, indexes) {
          var rowData = table.rows(indexes).data().toArray();
          // Enviar la solicitud con estos datos
          $scope.id = rowData[0].ID_EVENTO;

          // pedir al servidor las funciones ya registradas para 
          // el evento seleccionado
          $http({
            method: 'POST',
            url: $site.root + '/tablero/obtener/boletos',
            data: {
              id: $scope.id
            }
          }).then(function success(response) {
            $scope.salas = response.data;
          }, function error(response) {
            alert("Error al consultar al servidor");
          })

        })
        .on('deselect', function(e, dt, type, indexes) {
          // al deseleccionar limpiar la memoria
          var rowData = table.rows(indexes).data().toArray();
          $scope.id = '';
          $scope.salas = [];
        });
    });
  }])
  .controller('addEspectacularCtrl', ['$scope', function($scope) {
    /**
     * contralador para la funcion de agregar espectaculares
     */
    $scope.site = $site.root;
    $scope.map = getCenterMap();
    // este objeto me permite convertir de direcciones a coordenadas
    $scope.geocoder = new google.maps.Geocoder();

    $scope.ubicacion = '';

    $scope.geocodificar = function() {
      changeMap($scope.geocoder, $scope.map, $scope.ubicacion);
    }


  }])
  .controller('addPublicidadCtrl', ['$scope', function($scope) {
    /**
     * Controlador para la funcion de agregar espectacular
     */
    $scope.site = $site.root;
    $scope.id = 0;

    $(document).ready(function() {
/*      aqui pedimos los eventos y los mostramos en tabla via ajax
      al seleccionar se cambia el valor del hidden input con el correpondiente
      id del evento*/
      var table = $('#eventosTable').DataTable({
        "ajax": {
          url: $site.root + '/dash/obtener/eventos',
          type: "POST",
        },
        responsive: true,
        select: true,
        "columns": [{
          "data": "TITULO"
        }, {
          "data": 'NOMBRE'
        }, {
          "data": "FECHA_INICIO"
        }, {
          "data": "FECHA_FIN"
        }]
      })

      table
        // al deseleccionar se limpia el id del evento de la memoria 
        // y del hidden input
        .on('select', function(e, dt, type, indexes) {
          var rowData = table.rows(indexes).data().toArray();
          // Enviar la solicitud con estos datos          
          $scope.id = rowData[0].ID_EVENTO;
          $("input[name='id_evento']").val($scope.id);
        })
        .on('deselect', function(e, dt, type, indexes) {
          var rowData = table.rows(indexes).data().toArray();
          $scope.id = '';
        });
    });
    // al cambiar el valor de #cartel accinar la funcion archivo
    document.getElementById('cartel').addEventListener('change', archivo, false);

  }])
  .controller('addAnuncioCtrl', ['$http', '$scope', function($http, $scope) {
    /**
     * Controlador para la funcion de agregar anuncio
     */
    $scope.site = $site.root;
    // mostrar los datos de las selecciones en el formulario
    $scope.slugar = false;
    $scope.cargarAnuncios = function() {
      // al seleccionar un evento se obtiene toda la publicidad disponible 
      // para ese evento
      $http({
        method: 'POST',
        url: $site.root + '/obtener/publicidad/evento',
        data: {
          id: $scope.sevento.ID_EVENTO
        }
      }).then(function success(response) {
        $scope.publicidad = response.data;

      }, function error(response) {
        alert("Error al conectar con el servidor");
      })
    }

    $scope.map = getCenterMap();
    // crear un marcador sobre la ubicacion dada
    $scope.marker = new google.maps.Marker({
      map: $scope.map
    });
    // si cambia el lugar seleccionado volver a centrar el mapa
    $scope.$watch('slugar', function(newValue, oldValue) {
      $scope.map.setCenter({
        lat: parseFloat($scope.slugar.LATITUD),
        lng: parseFloat($scope.slugar.LONGITUD)
      });
      $scope.marker.setPosition({
        lat: parseFloat($scope.slugar.LATITUD),
        lng: parseFloat($scope.slugar.LONGITUD)
      });
    })

    // obtener todos los eventos activos
    $http({
      method: 'GET',
      url: $site.root + '/obtener/eventos/activos'
    }).then(function success(response) {
      $scope.eventos = response.data;
    }, function error(response) {
      alert("Error al conectar con el servidor");
    })
    // obtener todos los espectaculares registrados
    $http({
      method: 'GET',
      url: $site.root + '/obtener/espectaculares'

    }).then(function success(response) {
      $scope.ubicaciones = response.data;
    }, function error(response) {
      alert("Error al conectar con el servidor");
    })



  }])
  .controller('panelCtrl', ['$scope', '$http', function($scope, $http) {
    /**
     * contralador principal encargado de graficar datos en la pagina
     * principal del dashboard
     */

    // obtener todos los eventos activos
    $http({
      method: 'GET',
      url: $site.root + '/obtener/eventos/activos'
    }).then(function success(response) {
      $scope.eventos = response.data;
    }, function error(response) {
      alert("Error al conectar con el servidor");
    })

    // obtener las ventas de la semana
    $http({
      method: 'GET',
      url: $site.root + '/obtener/eventos/ventas'
    }).then(function success(response) {
      var datos = response.data;
      // dibujar las graficas de mas y menos vendidos
      var conteo = new Array();
      var ctx = document.getElementById("mas-vendidos");

      for (var i = 0; i < Object.keys(datos).length; i++) {
        conteo.push(parseInt(datos[Object.keys(datos)[i]].CANTIDAD));
      }
      // luego de agrupar las ventas por dia las grafica en forma de barras
      var mvendidos = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: Object.keys(datos),
          datasets: [{
            label: "ventas de la semana",
            data: conteo,
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

    $scope.selEvento = function() {
      // al seleccionar un evento obtiene las ventas por dia de dicho evento
      $http({
        method: 'POST',
        url: $site.root + '/tablero/obtener/boletos',
        data: {
          id: $scope.dsevento.ID_EVENTO
        }
      }).then(function success(response) {      
        $scope.salas = response.data;
      }, function error(response) {
        alert("Error al consultar al servidor");
      })
    }

    $scope.grafDisp = function() {
      // al seleccionar un evento grafica en pastel la disponibilidad de boletos
      if ($scope.dsevento != false) {
        $http({
          method: 'POST',
          url: $site.root + '/obtener/disponibilidad',
          data: {
            id: $scope.dsevento.ID_EVENTO,
            n_sala: $scope.nsevento.N_SALA
          }
        }).then(function success(response) {

          var ctx = document.getElementById("disponibilidad");
          var disp = parseInt(response.data.CAPACIDAD - response.data.CANTIDAD);
          var disponibilidad = new Chart(ctx, {
            type: 'pie',
            data: {
              labels: ['Disponibles', 'Vendidos'],
              datasets: [{
                label: "Disponibilidad del evento",
                data: [disp, parseInt(response.data.CANTIDAD)],
                fill: false,
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


    $scope.grafDia = function() {
      // obtener las ventas por dia del evento seleccionado
      $http({
        method: 'POST',
        url: $site.root + '/obtener/ventas/dia',
        data: {
          id: $scope.sevento.ID_EVENTO
        }
      }).then(function success(response) {
        console.log(response.data);
        var conteo = new Object();
        var datos = new Array();
        for (var i = 0; i < response.data.length; i++) {
          if (response.data[i].FECHA_VENTA in Object.keys(conteo)) {
            conteo[response.data[i].FECHA_VENTA] += response.data[i].CANTIDAD;
          } else {
            conteo[response.data[i].FECHA_VENTA] = response.data[i].CANTIDAD;
          }
        }

        for (var i = 0; i < Object.keys(conteo).length; i++) {
          datos.push(conteo[Object.keys(conteo)[i]]);
        }
        // luego de agrupar los datos se grafican en forma lineal
        var ctx = document.getElementById("vendidos-semana");

        var dvendidos = new Chart(ctx, {
          type: 'line',
          data: {
            labels: Object.keys(conteo),
            datasets: [{
              label: "ventas del evento en la semana",
              data: datos,
              fill: false,
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
    // obtener las estadisticas de usuarios por edad
    $http({
      method: 'GET',
      url: $site.root + '/info/edades'
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
      var mvendidos = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: edades,
          datasets: [{
            label: "Edades de los usuarios",
            data: conteo,
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
  .directive('addFuncion', [function() {
    // esta directiva crea nuevos campos para agregar funciones a un evento
    return {
      restrict: 'A',
      scope: {
        // su scope es unidireccional
        funcion: "@"
      },
      templateUrl: 'templates/funcion.html'
    };
  }])


/**
 * permite cambiar la posicion del mapa de acuerdo a los datos del 
 * geocodificador
 * @param geocoder geocodificador de google mapss
 * @param resultsMap mapa sobre el que mostrar la salida
 * @param address direccion a geocodificar
 */
function changeMap(geocoder, resultsMap, address) {
  // limitamos la geolocalizacion a san luis potosi
  geocoder.geocode({
      'address': address,
      componentRestrictions: {
        country: 'MX',
        locality: 'San Luis Potosi'
      }
    },
    function(results, status) {
      // geolocalizamos la direccion y colocamos un marcador
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
        document.getElementById("preview").innerHTML = ['<img class="img-thumbnail" src="', e.target.result, '"/>'].join('');
      };
    })(f);

    reader.readAsDataURL(f);
  }
}

// crea un mapa centrado
function getCenterMap() {
  var map = new google.maps.Map(document.getElementById('mapa'), {
    center: {
      lat: 22.151970,
      lng: -100.975427
    },
    zoom: 14
  });

  return map;
}