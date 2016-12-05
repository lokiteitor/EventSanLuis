<?php 
require_once 'DB.php';

/**
 * @file Model.php
 * @brief Este archivo almacena la clase correspondiente
 * a la clase padre de los modelos que permiten una abstraccion de la base
 * de datos
 */

/**
* @brief Clase base para los modelos de datos
* @detail Esta clase es una abstracion de la base de datos y nos permite
* utilizar las tablas como si fueran objetos php
*/
class Model extends DB
{
    // aqui se almacenan los atributos dinamicos
    // correspondientes a los campos de la tabla
    public $campos;  
    // nombre del campo id de la tabla
    // TODO que pasa en los compuestos
    public $idfield = 'ID';
    // por defecto es el nombre de la clase
    // en minusculas
    public $tablename;
    // valor del id
    public $ID;

    /**
     * @brief Constructor , abre la conexion a la base de datos y crea 
     * los atributos dinamicos correspondientes a los campos en la tabla
     */
    function __construct()
    {
        $this->conexion = self::conectar();
        // conseguir la estructura de la tabla
        if ($this->tablename == null) {
            $this->tablename = strtoupper(get_class($this));    

        }   

        $sql = 'SHOW FIELDS FROM '.$this->tablename;

        $result = $this->consultar($sql);

        $rows = $this->fetch($result);
        
        foreach ($rows as $field) {
            // crear variables variables con los nombre de loscampos
            $campo = $field['Field'];            
            // asignamos el atributo dinamico
            $this->campos[$campo] = null;
        }
    }

    /**
     * @brief buscar el registro con el id 
     * @param $id -- id del registro
     * @return devuelve un instancia del modelo con los valores del registro
     * si existe, en caso contrario retorna un objeto nuevo
     */

    public static function find($id)
    {
        $conexion = self::conectar();
        // obtener una instancia del modelo hijo
        $objecto = new static;

        $sql = 'SELECT * FROM '. $objecto->tablename .  ' WHERE '. $objecto->idfield. ' =?';

        $stmt = $conexion->prepare($sql);

        if (!$stmt) {

            die("fallo al preparar la consulta ". $conexion->error);
        }

        $stmt->bind_param('i',$id);
        if (!($stmt->execute())) {
            die('Error al consultar la base de datos '. $stmt->error);
        }
        // en este punto la consulta se llevo a cabo
        $result = $stmt->get_result();
        if ($result->num_rows >= 0 ) {
            // el registro si existe, llenarse los atributos del modelo
            $rows = $result->fetch_array(MYSQLI_ASSOC);

            foreach (array_keys($objecto->campos) as $ncampo) {
                $colname = $ncampo;
                $objecto->campos[$ncampo] = $rows[$ncampo];
            }
            $objecto->ID = $objecto->campos[$objecto->idfield];
        }
        $conexion->close();
        $result->free();
        $stmt->close();

        return $objecto;
    }


    /**
     * @brief Guarda los datos del alumno en la base de datos
     * @details actualiza o crea un nuevo registro en el que almacena los 
     * cambios hecho en el modelo
     * @return void
     */

    public function save()
    {
        // crear un nuevo registro
        if ($this->campos[$this->idfield] == null) {
            $sql = $this->getSQLNewRegister();   
        }
        else{
            //actualizar registro
            $sql = $this->getSQLUpdateRegister();
        }

        $stmt = $this->conexion->prepare($sql);

        if ($this->campos[$this->idfield] == null) {
            $this->newBind($stmt);
        }
        else{
            $this->updateBind($stmt);
        }
        // ejecutar la consulta
        $stmt->execute();        
        if ($stmt->errno) {
            die('Error al actualizar el registro '. $stmt->error);
        }
        $stmt->close();   

        $this->ID = $this->conexion->insert_id; 
        $this->campos[$this->idfield] = $this->ID;
    }


    // TODO : esta clase no se a probado debido a los problemas con las relaciones
    public function delete()
    {
        $sql = 'DELETE FROM ' . $this->tablename . ' WHERE '. $this->idfield.'='.$this->ID;
        echo $sql;
        $result = $this->consultar($sql);
    }

    /**
     * @brief genera el sql necesario para realizar un UPDATE en la base de datos
     * @return retorna el sql con los campos escapados para un UPDATE
     */
    private function getSQLUpdateRegister ()
    {
        $campossql = array();
        $sql = 'UPDATE '. $this->tablename . ' SET ';
        
        foreach (array_keys($this->campos) as $ncampo) {
            if ($ncampo != $this->idfield) {
                array_push($campossql, $ncampo.'=?');
            }
        }
        $sql = $sql . implode(' ,', $campossql);
        
        $sql = $sql .' WHERE '. $this->idfield . '=?';

        return $sql;   
    }

    /**
     * @brief genera sql necesario para realizar un INSERT
     * @return retorna sql escapado para un INSERT
     */
    private function getSQLNewRegister()
    {
        $sql ='INSERT INTO '.$this->tablename .' (';
        $campossql = array();

        foreach (array_keys($this->campos) as $ncampo) {
            if ($ncampo != $this->idfield) {
                array_push($campossql, $ncampo);
            }

        }            
        $sql = $sql . implode(',', $campossql);
        $sql = $sql.') VALUES (';

        for ($i=0; $i < count($this->campos)-1; $i++) { 
            $sql = $sql . '?';
            if ($i != count($this->campos)-2) {
                $sql = $sql . ',';
            }
        }
        $sql = $sql.')';
              
        return $sql;
    }


    /**
     * @brief metodo magico que permite obtener el valor de un atributo 
     * dinamico, necesario para obtener el valor de un campo
     * @param $name, nombre del atributo
     * @return retorna el valor del atributo
     */
    public function &__get($name)
    {
        // obtiene el valor del atributo dinamico

        if (array_key_exists($name, $this->campos)) {
            return $this->campos[$name];
        }

        // lanzamos el error si no existe ese atributo
        $trace = debug_backtrace();
        trigger_error(
            'Propiedad indefinida mediante __get(): ' . $name .
            ' en ' . $trace[0]['file'] .
            ' en la línea ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;

    }

    /**
     * @brief verifica que el atributo dinamico exista
     * @return booleno que indica si existe(true) o no (false)
     */

    public function __isset($name)
    {
        return isset($this->campos[$name]);
    }

    /**
     * @brief fija el valor de una atributo dinamico
     * @param $name , nombre del atributo
     * @param $value , valor a asignar
     * @return retorna null en caso de error, lanza una excepcion
     */
    public function __set($name,$value)
    {

        if (array_key_exists($name, $this->campos)) {
            $this->campos[$name] = $value;
        }
        else{
            $trace = debug_backtrace();
            trigger_error(
                'Propiedad indefinida mediante __set(): ' . $name .
                ' en ' . $trace[0]['file'] .
                ' en la línea ' . $trace[0]['line'],
                E_USER_NOTICE);
            return null;            
        }
    }

}

 ?>
