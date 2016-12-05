<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @class Publicidad
	* @brief Esta clase hace referencia a la tabla publicidad en la base de datos
	*/
	class Publicidad extends Model implements Bindeable
	{

		public $idfield = 'ID_PUBLICIDAD';


		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('dsss', $this->ID_EVENTO, $this->FECHA_INICIO, $this->FECHA_FIN, $this->URL_IMAGEN);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('dsssd', $this->ID_EVENTO, $this->FECHA_INICIO, $this->FECHA_FIN, $this->URL_IMAGEN, $this->ID_PUBLICIDAD);
		}
		/**
		 * @brief obtiene los registros de publicidad activos 
		 * @param $id id del evento
		 */
		public function getActivos($id)
		{
			$sql = 'SELECT EV.ID_EVENTO, PB.ID_PUBLICIDAD, EV.TITULO ,PB.FECHA_INICIO,PB.FECHA_FIN,
					PB.URL_IMAGEN FROM EVENTO AS EV INNER JOIN PUBLICIDAD AS PB ON EV.ID_EVENTO=PB.ID_EVENTO
					WHERE PB.FECHA_INICIO<=CURDATE() AND PB.FECHA_FIN>=CURDATE() AND PB.ID_EVENTO=?';

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