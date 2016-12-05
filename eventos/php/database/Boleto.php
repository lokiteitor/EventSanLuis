<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @brief Esta clase hace referencia a la tabla boleto en la base de datos
	*/
	class Boleto extends Model implements Bindeable
	{

		public $idfield = 'ID_BOLETO';


		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('ddddd',$this->PRECIO, $this->NINO, $this->ESTUDIANTE, $this->ADULTO, $this->VEJEZ);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('dddddd',$this->PRECIO, $this->NINO, $this->ESTUDIANTE, $this->ADULTO, $this->VEJEZ, $this->ID_BOLETO);
		}

	}
	
 ?>