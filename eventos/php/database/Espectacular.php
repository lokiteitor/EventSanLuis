<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @brief Esta clase hace referencia a la tabla espectacular en la base de datos
	*/
	class Espectacular extends Model implements Bindeable
	{

		public $idfield = 'ID_ESPECTACULAR';


		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('sddss',$this->UBICACION,$this->LATITUD,$this->LONGITUD, $this->ZONA,$this->TIPO);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('sddssi', $this->UBICACION,$this->LATITUD,$this->LONGITUD, $this->ZONA, $this->TIPO,$this->ID_ESPECTACULAR);
		}

		public function getInfoAll()
		{
			$sql = 'SELECT * FROM ESPECTACULAR';
            $result = $this->consultar($sql);
            return $this->fetch($result);
		}
	}
	
 ?>