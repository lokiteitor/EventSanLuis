<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @brief Esta clase hace referencia a la tabla partida_venta en la base de datos
	*/
	class PartidaVenta extends Model implements Bindeable
	{

		public $idfield = 'FOLIO';
		
		public $tablename = 'PARTIDA_VENTA';

		function __construct()
		{			
			parent::__construct();
		}
 
		public function newBind($stmt)
		{
			$stmt->bind_param('iiiis',$this->FOLIO ,$this->N_REGISTRO,$this->ID_BOLETO, $this->CANTIDAD, $this->TIPO);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('iisi', $this->ID_BOLETO,$this->CANTIDAD, $this->TIPO, $this->FOLIO,$this->N_REGISTRO);
		}


	    private function getSQLNewRegister()
	    {
	    	$sql = 'INSERT INTO PARTIDA_VENTA (FOLIO,N_REGISTRO,ID_BOLETO,CANTIDAD,TIPO) VALUES (?,?,?,?,?)';
	              
	        return $sql;
	    }


	    private function getSQLUpdateRegister ()
	    {
	    	$sql = 'UPDATE PARTIDA_VENTA SET ID_BOLETO=?,CANTIDAD=?, TIPO=?, WHERE FOLIO =? AND WHERE N_REGISTRO=?';

	        return $sql;   
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

	}
	
 ?>