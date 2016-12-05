<?php 

    require_once 'php/include/Model.php';
    require_once 'php/include/Bindeable.php';

    /**
    * @class Venta
    * @brief Esta clase hace referencia a la tabla usuario en la base de datos
    */
    class Venta extends Model implements Bindeable
    {

        public $idfield = 'FOLIO';


        function __construct()
        {
            parent::__construct();
        }

        public function newBind($stmt)
        {
            $stmt->bind_param('iiis',$this->ID_USUARIO,$this->ID_EVENTO,$this->N_SALA,$this->FECHA_VENTA);
        }

        public function updateBind($stmt)
        {
            $stmt->bind_param('iiisi',$this->ID_USUARIO,$this->ID_EVENTO,$this->N_SALA,$this->FECHA_VENTA,$this->FOLIO);
        }

        /**
         * @brief obtiene la cantidad de ventas de un determinado evento
         * @param $id id del evento
         */

        public function getVentas($id)
        {

            $sql = 'SELECT PV.CANTIDAD, V.N_SALA FROM VENTA as V INNER JOIN PARTIDA_VENTA as PV ON  PV.FOLIO=V.FOLIO WHERE V.ID_EVENTO=?';
            
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