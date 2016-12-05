<?php 
/**
 * @file Route.php
 * @brief maneja el enrutado en todo el sitio
 */

require_once 'config.php';
require_once 'Auth.php';
/**
* @class Route
* @brief encargada de manejar el enrutado a lo largo de todo el sitio 
* permite tener pretty urls 
*/
class Route
{
    private static $POST = []; /**< almacena las rutas de tipo POST */
    private static $GET = []; /**< almacena las rutas de tipo GET */
    private static $login = []; /**< almacena las rutas que requieren login */
    private static $Admin = []; /**< almacena las rutas reservadas al admnistrador*/
    // TODO : nos vendria bien un diccionario

    /**
     * @brief registra una ruta de tipo POST
     * @param $ruta , ruta a registrar
     * @param $filter , arreglo con los filtros asignados
     * @param $funcion , callback a ejecutar con esta ruta
     */

    public static function post($ruta,$filter,$funcion)
    {
        global $site;   
        // asociar la rutas tipo post con la funcion 
        array_push(self::$POST,[$site['root'].$ruta,$funcion]);
        self::addFilter($site['root'].$ruta,$filter);

    }

    /**
     * @brief registra las rutas de tipo GET
     * @param $ruta , ruta a registrar
     * @param $filter , arreglo con los filtros asignados
     * @param $funcion , callback a ejecutar con esta ruta
     */

    public static function get($ruta,$filter,$funcion)
    {
        global $site;
        // asociar la rutas tipo get con la funcion 
        array_push(self::$GET,[$site['root'].$ruta,$funcion]);        
        self::addFilter($site['root'].$ruta,$filter);
    }

    /**
     * @brief agregar una filtro a la ruta
     * @param $ruta , URL sobre la que agregar la ruta
     * @param $filter , tipo de filtro para asignar
     */

    public static function addFilter($ruta,$filter)
    {
        global $site;
        if (in_array('login', $filter)) {
            // el usuario tiene que estar autentificado
            array_push(self::$login,$ruta);
        }
        if (in_array('admin', $filter)) {
            array_push(self::$Admin,$ruta);
        }        
    }    

    /**
     * @brief enruta una peticion de tipo POST
     */

    private static function dirigirPost()
    {
        $status = false;
        // sabemos que la consulta es de tipo post dirigir la ruta 
        // al controlador adecuado
        foreach (self::$POST as $registro) {
            if ($_SERVER['REQUEST_URI'] == $registro[0]) {
                // en la ruta que coincida ejecutar su funcion
                call_user_func($registro[1]);
                $status = true;
            }
        }

        if (!$status) {
            header("HTTP/1.0 404 Not Found");
        }
    }
    
    /**
     * @brief enruta una peticion de tipo GET
     */
    private static function dirigirGet()
    {
        $status = false;
        if ($_SERVER['QUERY_STRING'] != "") {
            $ruta = str_replace('?'.$_SERVER['QUERY_STRING'], '',$_SERVER['REQUEST_URI']);
        }
        else{
            $ruta = $_SERVER['REQUEST_URI'];
        }

        foreach (self::$GET as $registro) {
            if ($ruta == $registro[0]) {
                // en la ruta que coincida ejecutar su funcion
                $status = true;
                call_user_func($registro[1]);
            }
        }        
        if (!$status) {
            header("HTTP/1.0 404 Not Found ErrorDocument 404 /404.html");
        }        
    }

    /**
     * @brief dispara el enrutado , es el proceso de inicio en el sitio
     */
    public static function dispatch()
    {       
        $auth = new Auth();

        if (in_array($_SERVER['REQUEST_URI'],self::$login)) {
            $auth->filterLogin();
        }


        if (in_array($_SERVER['REQUEST_URI'],self::$Admin)) {
            $auth->filterAdmin();
        }


        // de acuerdo a la ruta de la consulta despachar las solicitudes
        if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
            self::dirigirPost();
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            self::dirigirGet();
        }
    }
}
 ?>