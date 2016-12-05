<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @class Usuario
	* @brief Esta clase hace referencia a la tabla usuario en la base de datos
	*/
	class Usuario extends Model implements Bindeable
	{

		public $idfield = 'ID_USUARIO';


		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('ssssssssssd',$this->NOMBRE,$this->APELLIDOS,$this->SEXO,$this->FECHA_NAC,$this->EMAIL,$this->TELEFONO,$this->DIRECCION,$this->RFC, $this->USERNAME, $this->PASSWORD, $this->ACTIVO);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('ssssssssssdd',$this->NOMBRE,$this->APELLIDOS,$this->SEXO,$this->FECHA_NAC,$this->EMAIL,$this->TELEFONO,$this->DIRECCION,$this->RFC, $this->USERNAME, $this->PASSWORD, $this->ACTIVO,$this->ID_USUARIO);
		}
		/**
		 * @brief obtiene la informacion de un usuario con el $id especificado
		 * @param $id id del usuario
		 */
		public function getInfo($id)
		{
			$sql = 'SELECT NOMBRE , APELLIDOS , SEXO , FECHA_NAC , EMAIL , TELEFONO,
					DIRECCION , RFC FROM USUARIO WHERE ID_USUARIO=?';

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
		/**
		 * @brief obtiene las estadisticas de edad de los usuarios
		 */
		public function infoEdades()
		{
			$sql = 'SELECT FECHA_NAC FROM USUARIO ORDER BY FECHA_NAC';
            $result = $this->consultar($sql);
            return $this->fetch($result);		
		}

	}
	
 ?>