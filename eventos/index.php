<?php 
// Librerias del motor
require_once 'php/include/Auth.php';
require_once 'php/include/Route.php';
require_once 'php/include/Views.php';
require_once 'php/include/Url.php';
require_once 'php/include/config.php';
require_once 'php/include/Email.php';
// librerias de terceros
require_once 'vendor/autoload.php';
// modelos
require_once 'php/database/Boleto.php';
require_once 'php/database/Espectacular.php';
require_once 'php/database/Evento.php';
require_once 'php/database/Lugar.php';
require_once 'php/database/PartidaEvento.php';
require_once 'php/database/PartidaVenta.php';
require_once 'php/database/PartidaPublicidad.php';
require_once 'php/database/Publicidad.php';
require_once 'php/database/Venta.php';
// controladores
require_once 'php/controllers/UsuarioController.php';
require_once 'php/controllers/LugarController.php';
require_once 'php/controllers/EventoController.php';
require_once 'php/controllers/PublicidadController.php';

/**
 * @file index.php
 * @brief Este archivo dirige todas las solicitudes al componente adecuado
 * para ello todas las solicitudes estan redirigidas atraves de apache
 */


/*Rutas a las que el usuario normal puede acceder*/

// esta ruta corresponde al index
Route::get('/',[],function ()
{
   View('index');
});

Route::get('/login',[],function ()
{
    View('login');
});

// recibe los datos del login
Route::post('/login',[],function ()
{
    $auth = new Auth();

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $auth->setAuth(array(
            'username' => $_POST['username'],
            'password' => $_POST['password']
        ));
    }
    else{
        header("Location: ".getUrl('/login'));
    }

    if ($auth->isAuth() && $auth->isAdmin()) {
        header("Location: ".getUrl('/admin'));
    }
    else if ($auth->isAuth()) {
        header("Location: ".getUrl('/tablero'));
    }
    else{
        header("Location: ".getUrl('/login'));
    }

    return;
});


Route::get('/registro',[],function ()
{
    View('registro');
});


Route::post('/registro',[],function ()
{
    $ctrl = new UsuarioController();
    if ($ctrl->registrar()) {
        header("Location: ".getUrl('/user/settings'));
    }    
});

// todos pueden ver esta session con el fin de atraer el publico
Route::get('/tablero',[],function ()
{
    View('tablero');
});


Route::get('/comprar',['login'],function ()
{
    View('compra');
});



Route::post('/comprar',['login'],function ()
{
    $boletos = 0;

    // obtener informacion del usuario para el correo
    // guardar los datos en cookies y mostrar un formulario para 
    // la informacion bancaria
    if (isset($_POST['cantidadAdulto'])) {
        setcookie('adultos',$_POST['cantidadAdulto']);
        $boletos += $_POST['cantidadAdulto'];
    }

    if (isset($_POST['cantidadNino'])) {
        setcookie('ninos',$_POST['cantidadNino']);
        $boletos += $_POST['cantidadNino'];
    }

    if (isset($_POST['cantidadEstudiante'])) {
        setcookie('estudiantes',$_POST['cantidadEstudiante']);
        $boletos += $_POST['cantidadEstudiante'];
    }

    if (isset($_POST['cantidadVejez'])) {
        setcookie('ancianos',$_POST['cantidadVejez']);
        $boletos += $_POST['cantidadVejez'];
    }

    if (isset($_POST['id_evento'])) {
        setcookie('id_evento',$_POST['id_evento']);
    }
    else{
        $boletos = 0;
    }

    if (isset($_POST['n_sala']) && $_POST['n_sala'] != "") {
        setcookie('n_sala',$_POST['n_sala']);
    }
    else{
        $boletos = 0;
    }
    // redirigir al formulario
    if ($boletos > 0) {
        header("Location: ".getUrl('/comprar'));            
    }
    else{
        // no selecciono todos los datos necesarios
        header("Location: ".getUrl('/tablero'));
    }
    

});

Route::get('/user/settings',['login'],function ()
{
    View('perfil');
});

Route::post('/user/settings',['login'],function ()
{
    $ctrl = new UsuarioController();
    $auth = new Auth();

    $ctrl->perfil($auth->getID());

    header("Location: ".getUrl('/tablero'));
});


Route::post('/cambiar/password',['login'],function ()
{
    $ctrl = new UsuarioController();
    $auth = new Auth();

    $ctrl->setPassword($auth->getID());

    header("Location: ".getUrl('/tablero')); 
});


Route::get('/obtener/info/user',['login'],function ()
{
    // obtener el id
    $auth = new Auth();
    $user = new Usuario();

    $id = $auth->getID();
    // obtener la informacion de la base de datos
    echo json_encode($user->getInfo($id));
});

Route::post('/confirmar/compra',['login'],function ()
{
    // validar los datos de entrada y realizar la compra si procede
    $venta = new Venta();
    $auth = new Auth();

    // utilizado para la generacion del ticket
    $id = $auth->getID();
    $user = Usuario::find($id);
    //$ticket = new Ticket();

    $venta->ID_USUARIO = $user->ID_USUARIO;

    if (isset($_COOKIE['id_evento'])) {
        $venta->ID_EVENTO = $_COOKIE['id_evento'];
        $evento = Evento::find($_COOKIE['id_evento']);
    }
    else{
        header("Location: ".getUrl('/error/compra'));
    }

    if (isset($_COOKIE['n_sala'])) {
        $venta->N_SALA = $_COOKIE['n_sala'];
    }
    else
        header("Location: ".getUrl('/error/compra'));    

    $time = new DateTime();
    $venta->FECHA_VENTA = $time->format('Y-m-d');

    $venta->save();
    

    if (isset($_COOKIE['adultos']) && $_COOKIE['adultos'] != 0) {
        $pventa = new PartidaVenta();
        $pventa->FOLIO = $venta->FOLIO;
        $pventa->N_REGISTRO = 1;
        $pventa->ID_BOLETO = $evento->ID_BOLETO;
        $pventa->CANTIDAD = $_COOKIE['adultos'];
        $pventa->TIPO = 'adulto';
        $pventa->savePartida(false);
    }

    if (isset($_COOKIE['ancianos']) && $_COOKIE['ancianos'] != 0) {
        $pventa = new PartidaVenta();
        $pventa->FOLIO = $venta->FOLIO;
        $pventa->N_REGISTRO = 2;
        $pventa->ID_BOLETO = $evento->ID_BOLETO;
        $pventa->CANTIDAD = $_COOKIE['ancianos'];
        $pventa->TIPO = 'vejez';
        $pventa->savePartida(false);
    }

    if (isset($_COOKIE['estudiantes']) && $_COOKIE['estudiantes'] != 0) {
        $pventa = new PartidaVenta();
        $pventa->FOLIO = $venta->FOLIO;
        $pventa->N_REGISTRO = 3;
        $pventa->ID_BOLETO = $evento->ID_BOLETO;
        $pventa->CANTIDAD = $_COOKIE['estudiantes'];
        $pventa->TIPO = 'estudiante';
        $pventa->savePartida(false);        
    }
    if (isset($_COOKIE['ninos']) && $_COOKIE['ninos'] != 0) {
        $pventa = new PartidaVenta();
        $pventa->FOLIO = $venta->FOLIO;
        $pventa->N_REGISTRO = 4;
        $pventa->ID_BOLETO = $evento->ID_BOLETO;
        $pventa->CANTIDAD = $_COOKIE['ninos'];
        $pventa->TIPO = 'nino';
        $pventa->savePartida(false);
    }
    // eliminar las cookies

    setcookie('adultos','',time()-1000);
    setcookie('ancianos','',time()-1000);
    setcookie('estudiantes','',time()-1000);
    setcookie('ninos','',time()-1000);
    setcookie('id_evento','',time()-1000);
    setcookie('n_sala','',time()-1000);

    $email = new Email();        
    if (isset($_POST['email'])) {
        $email->setTo($_POST['email']);
    }
    //$email->folio = $venta->FOLIO;
    $email->send();        
    header("Location: ".getUrl('/confirmado'));

});


Route::get('/buscador',['login'],function ()
{
   $evento = new Evento;
   
   echo json_encode($evento->buscarEvento($_GET['term']));

});


Route::get('/confirmado',['login'],function ()
{
    View('confirmacion');
});



/*Rutas comunes a los dos tipos de usuarios*/
Route::get('/logout',['login'],function ()
{
    // destruir la session
    $auth = new Auth();
    $auth->logout();
});




/* Rutas accesibles unicamente al administrador*/
Route::get('/admin',['login','admin'],function ()
{
    View('admin');
});

/* Estas rutas pertenecen a la API */
Route::get('/tablero/obtener/eventos',[],function ()
{
    $eventos = new Evento();
    echo json_encode($eventos->getEventos());
       
});

Route::post('/obtener/busqueda',[],function ()
{
    $eventos = new Evento();
    $json = file_get_contents('php://input');
    $post = json_decode($json);   
    echo json_encode($eventos->getEventosBusqueda($post->titulo));
});

Route::post('/tablero/obtener/boletos',[],function ()
{
   
   $peventos = new PartidaEvento();

    $json = file_get_contents('php://input');
    $post = json_decode($json);
    echo json_encode($peventos->getFunciones($post->id));

});

// obtiene la cantidad de ventas vendidas
Route::post('/obtener/ventas',[],function ()
{
    $ventas = new Venta();
    $suma = 0;
    $json = file_get_contents('php://input');
    $post = json_decode($json);
    
    echo json_encode($ventas->getVentas($post->id));

});



Route::get('/obtener/info/evento',[],function ()
{
   $evento = new Evento();

   if (isset($_COOKIE['id_evento'])) {
       $id = $_COOKIE['id_evento'];

       echo json_encode($evento->getInfoEvento($id));
   }
    // TODO : regresar codigo de error
});


Route::post('/obtener/lugar/evento',[],function ()
{
 
    $lugar = new Lugar();
    $json = file_get_contents('php://input');
    $post = json_decode($json);    
    echo json_encode($lugar->getLugarEvento($post->id));

});

Route::get('/obtener/lugares/todos',[],function ()
{
    $lugar = new Lugar();

    echo json_encode($lugar->getAllLugar());
});

Route::post('/dash/obtener/eventos',[],function ()
{
    $eventos = new Evento();

    $result = $eventos->getEventosUbicacion();

    $result = '{"data":'.json_encode($result).'}';
    header('Content-Type: application/json');    
    echo $result;

});



Route::post('/modificar/funcion',['login','admin'],function ()
{
    $ctrl = new EventoController();

    $ctrl->modificarFuncion();

    header("Location: ".getUrl('/admin#/crear/funciones'));

});


Route::post('/registrar/lugar',['login','admin'],function ()
{
    $ctrl = new LugarController();
    $ctrl->registrar();

    header("Location: ".getUrl('/admin#/modificar/lugar'));

});

Route::post('/modificar/lugar',['login','admin'],function()
{
   
    $ctrl = new LugarController();
    $ctrl->modificar($_POST['selectlugar']);
    header("Location: ".getUrl('/admin#/modificar/lugar'));
});


Route::post('/registrar/evento',['login','admin'],function ()
{
    $ctrl = new EventoController();
    $ctrl->registrar();
    header("Location: ".getUrl('/admin#/crear/evento'));
});

Route::post('/registrar/funciones',['login','admin'],function ()
{
   $ctrl = new EventoController();
   $ctrl->registrarFunciones();
   header("Location: ".getUrl('/admin#/crear/funciones'));
});

Route::post('/registrar/espectacular',['login','admin'],function ()
{
    $ctrl = new PublicidadController();
    $ctrl->registrarEspectacular();
    header("Location: ".getUrl('/admin#/crear/evento'));
});

Route::post('/registrar/publicidad',['login','admin'],function ()
{
    $ctrl = new PublicidadController();
    $ctrl->registrarPublicidad();
    header("Location: ".getUrl('/admin#/registrar/publicidad'));

});

Route::get('/obtener/eventos/activos',['login','admin'],function ()
{
    $eventos = new Evento();
    header('Content-Type: application/json');    
    echo json_encode($eventos->getActivos());
});

Route::post('/obtener/publicidad/evento',['login','admin'],function ()
{
    $publicidad = new Publicidad();
    $json = file_get_contents('php://input');
    $post = json_decode($json);    
    echo json_encode($publicidad->getActivos($post->id));    
});

Route::get('/obtener/espectaculares',['login','admin'],function ()
{
    $espectaculares = new Espectacular();
    header('Content-Type: application/json');    
    echo json_encode($espectaculares->getInfoAll());
});


Route::post('/agregar/anuncio',[],function ()
{
   $ppublicidad = new PartidaPublicidad();

   if (isset($_POST['selectpb'])) {
       $ppublicidad->ID_PUBLICIDAD = $_POST['selectpb'];
   }
   else{
        //error, redirigir
        header("Location: ".getUrl('/admin#/registrar/anuncio'));
   }

   if (isset($_POST['selectlugar'])) {
       $ppublicidad->ID_ESPECTACULAR = $_POST['selectlugar'];
   }
   else{
        //error, redirigir
        header("Location: ".getUrl('/admin#/registrar/anuncio'));
   }

   $ppublicidad->ACTIVO = true;
   $ppublicidad->savePartida(false);
   header("Location: ".getUrl('/admin#/registrar/anuncio'));

});




// Rutas definidas para obtener los datos a graficar

Route::get('/info/edades',['login','admin'],function ()
{
    $usuario = new Usuario();
    // calcular los grupos de edades en pasos de 5 años

    $data = $usuario->infoEdades();

    $edades = array();

    for ($i=0; $i < 100; $i+=5) { 
        $edades[$i] = 0;
    }

    foreach ($data as $reg) {
        // convertir y comparar  tiempo
        $nacimiento = new DateTime($reg['FECHA_NAC']);
        $actual = new DateTime();
        for ($i=count($edades)-1; $i > 0 ; $i--) { 
            $diferencia = $actual->diff($nacimiento);
            if (array_keys($edades)[$i] <= $diferencia->y) {
                $edades[array_keys($edades)[$i]] +=1;
                break;
            }
        }

    }

    header('Content-Type: application/json');
    echo json_encode($edades);


});

Route::get('/obtener/eventos/ventas',['login','admin'],function ()
{
    // obtener los eventos mas vendidos de la semana
    $eventos = new Evento();

    $datos = $eventos->getVentas();

    $conteo = array();

    while (($row = $datos->fetch_assoc()) != null) {

        if (array_key_exists($row['TITULO'], $conteo)) {
            $conteo[$row['TITULO']]['CANTIDAD'] += $row['CANTIDAD'];
        }
        else{
            $conteo[$row['TITULO']]['CANTIDAD'] = $row['CANTIDAD'];
        }
    }

    // ordenar de mayor a menor
    $max = 0;
    $mayores = array();

    for ($i=0; $i < 5; $i++) { 
        $max = array_keys($conteo)[$i];
        for ($x=$i; $x < count(array_keys($conteo)); $x++) { 
            if ($conteo[$max]['CANTIDAD'] < $conteo[array_keys($conteo)[$x]]['CANTIDAD']) {
                $max = array_keys($conteo)[$x];
            }
        }
        $mayores[$max] = $conteo[$max];
        unset($conteo[$max]);
    }    

    header('Content-Type: application/json');
    echo json_encode($mayores);
});

Route::post('/obtener/ventas/dia',['login','admin'],function ()
{
    $eventos = new Evento();
    $json = file_get_contents('php://input');
    $post = json_decode($json);    
    header('Content-Type: application/json');
    echo json_encode($eventos->getVentasPorDia($post->id));
});

Route::post('/obtener/disponibilidad',['login','admin'],function ()
{
    $eventos = new Evento();
    $json = file_get_contents('php://input');
    $post = json_decode($json);

    $datos = $eventos->getDisponibilidad($post->id,$post->n_sala);
    $respuesta = array('CANTIDAD'=>0,'CAPACIDAD'=>$datos[0]['CAPACIDAD']);
    foreach ($datos as $reg) {
        $respuesta['CANTIDAD'] += $reg['CANTIDAD'];
    }
    header('Content-Type: application/json');

    echo json_encode($respuesta);
});

Route::dispatch();

 ?>
