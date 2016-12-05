<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
    * @class PartidaEvento
	* @brief Esta clase hace referencia a la tabla partida_evento en la base de datos
	*/
	class PartidaEvento extends Model implements Bindeable
	{
		
		public $tablename = 'PARTIDA_EVENTO';

		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('iissi',$this->ID_EVENTO, $this->N_SALA, $this->HORA_INICIO, $this->HORA_FIN, $this->CAPACIDAD);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('issiii', $this->N_SALA, $this->HORA_INICIO, $this->HORA_FIN, $this->CAPACIDAD,$this->N_SALA,$this->ID_EVENTO);
		}

        private function getSQLNewRegister()
        {
            // TODO ; no funciona
            $sql = 'INSERT INTO PARTIDA_EVENTO (ID_EVENTO,N_SALA,HORA_INICIO,HORA_FIN,CAPACIDAD) VALUES (?,?,?,?,?)';
                  
            return $sql;
        }


        private function getSQLUpdateRegister ()
        {
            $sql = 'UPDATE PARTIDA_EVENTO SET N_SALA=?, HORA_INICIO=?,HORA_FIN=?,CAPACIDAD=? WHERE N_SALA=? AND ID_EVENTO=?';

            return $sql;   
        }
        /**
         * @brief Buscar un registro con los ids proporcianados
         * @param $id_evento id del evento (clave primaria)
         * @param $n_sala sala donde se desarrolla (clave primaria)
         */
        public static function findPartida($id_evento,$n_sala)
        {
            $conexion = self::conectar();
            // obtener una instancia del modelo hijo
            $objecto = new static;

            $sql = 'SELECT * FROM PARTIDA_EVENTO WHERE ID_EVENTO=? AND N_SALA=?';

            $stmt = $conexion->prepare($sql);

            if (!$stmt) {

                die("fallo al preparar la consulta ". $conexion->error);
            }

            $stmt->bind_param('ii',$id_evento,$n_sala);
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
                $objecto->ID = $objecto->campos['ID_EVENTO'];
            }
            $conexion->close();
            $result->free();
            $stmt->close();

            return $objecto;
        }       
            
        public function savePartida($modify)
        {
            // crear un nuevo registro
            if ($modify == false) {
                $sql = $this->getSQLNewRegister();   
            }
            else{
                //actualizar registro
                $sql = $this->getSQLUpdateRegister();
            }

            $stmt = $this->conexion->prepare($sql);

            if ($modify == false) {
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
        }        
        /**
         * @brief obtiene las funciones registradas para un evento
         * @param $id  id del evento
         */
        public function getFunciones($id)    
        {
        	$sql = 'SELECT PV.N_SALA ,PV.CAPACIDAD, PV.HORA_INICIO,PV.HORA_FIN ,BL.PRECIO,BL.NINO,
                    BL.ESTUDIANTE,BL.VEJEZ,BL.ADULTO FROM PARTIDA_EVENTO as PV
                    INNER JOIN EVENTO as EV ON EV.ID_EVENTO=PV.ID_EVENTO 
                    INNER JOIN BOLETO as BL ON EV.ID_BOLETO=BL.ID_BOLETO
                    WHERE PV.ID_EVENTO=?';
        	
        	$stmt = $this->conexion->prepare($sql);

        	$stmt->bind_param('i',$id);
            
            $stmt->execute();        
            if ($stmt->errno) {
                die('Error al consultar el registro '. $stmt->error);
            }
            $result = $stmt->get_result();
            $stmt->close();   

            return $this->fetch($result);
        }

	}
	
 ?>