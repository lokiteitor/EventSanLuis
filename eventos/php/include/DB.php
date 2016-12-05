<?php 
/**
 * @file Esta parte se encarga de las primitivas de la base de datos
 * comunes a todos los modelos 
 */
require_once 'config.php';

/**
* @class DB
* @brief realiza las operaciones basicas con la base de datos
*/
class DB
{
    public $conexion;

    public function __construct()
    {
        $this->conexion = self::conectar();
    }

    /**
     * @brief realiza la conexion de la base de datos
     * @return retorna la conexion con la base de datos
     */
    public static function conectar()
    {     
        global $db;
        $conexion  = new mysqli($db['host'],$db['user'],$db['password'],$db['dbname']);

        if (!$conexion) {
            // fallo la conexion a la base de datos
            die('Error al conectar con la base de datos' . $conexion->connect_error);
        }        
        return $conexion;
    }

    /**
     * @brief realiza una consulta no escapada a la base de datos
     * @return retorna un conjunto de resultados de la consulta
     */
    public function consultar($sql)
    {
        $result = $this->conexion->query($sql);

        if (!$result) {
            die('Error al ejecutar la consulta ' . $this->conexion->error);
        }        
        return $result;        
    }

    /**
     * @brief convierte el resultado de la consulta en un arreglo asociativo
     * @param $result conjunto de resultados de la base de datos
     * @return retorna un arreglo asociativo con los resultados de la consulta
     */
    public function fetch($result)
    {
        $response = array();

        while ($rows = $result->fetch_assoc()) {
            array_push($response, $rows);
        }
        return $response;
    }

    function __destruct()
    {
        $this->conexion->close();

    }   

}


 ?>