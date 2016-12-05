<?php 

    require_once 'php/include/Model.php';
    require_once 'php/include/Bindeable.php';

    /**
    * @class PartidaPublicidad
    * @brief Esta clase hace referencia a la tabla usuario en la base de datos
    */
    class PartidaPublicidad extends Model implements Bindeable
    {

        public $tablename = 'REG_PUBLICIDAD';


        function __construct()
        {
            parent::__construct();
        }

        public function newBind($stmt)
        {
            $stmt->bind_param('iii',$this->ID_PUBLICIDAD,$this->ID_ESPECTACULAR,$this->ACTIVO);
        }

        public function updateBind($stmt)
        {
            $stmt->bind_param('iii',$this->ID_PUBLICIDAD,$this->ID_ESPECTACULAR,$this->ACTIVO);   
        }

        private function getSQLNewRegister()
        {
            $sql = 'INSERT INTO REG_PUBLICIDAD (ID_PUBLICIDAD,ID_ESPECTACULAR,ACTIVO) VALUES (?,?,?)';
                  
            return $sql;
        }


        private function getSQLUpdateRegister ()
        {
            $sql = 'UPDATE REG_PUBLICIDAD SET ACTIVO=? WHERE ID_PUBLICIDAD=? AND ID_PUBLICIDAD=?';

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