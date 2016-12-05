<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @brief Esta clase hace referencia a la tabla lugar en la base de datos
	*/
	class Lugar extends Model implements Bindeable
	{

		public $idfield = 'ID_LUGAR';


		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('ssissdd',$this->NOMBRE,$this->CALLE, $this->N_EXT, $this->TELEFONO, $this->EMAIL,$this->LATITUD,$this->LONGITUD);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('ssissddi',$this->NOMBRE,$this->CALLE, $this->N_EXT, $this->TELEFONO, $this->EMAIL,$this->LATITUD,$this->LONGITUD, $this->ID_LUGAR);
		}

		public function getLugarEvento($id)
		{
			$sql = 'SELECT LG.CALLE , LG.N_EXT , LG.TELEFONO , LG.EMAIL , LG.LATITUD , LG.LONGITUD 
					FROM EVENTO AS EV INNER JOIN LUGAR AS LG ON LG.ID_LUGAR=EV.ID_LUGAR
					WHERE EV.ID_EVENTO=?';
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

        public function getAllLugar()
        {
            $sql = 'SELECT * FROM LUGAR';

            $result = $this->consultar($sql);

            return $this->fetch($result);
        }

	}
	
 ?>