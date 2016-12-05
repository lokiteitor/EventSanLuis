<?php 

	require_once 'php/include/Model.php';
	require_once 'php/include/Bindeable.php';

	/**
	* @brief Esta clase hace referencia a la tabla evento en la base de datos
	*/
	class Evento extends Model implements Bindeable
	{

		public $idfield = 'ID_EVENTO';


		function __construct()
		{
			parent::__construct();
		}

		public function newBind($stmt)
		{
			$stmt->bind_param('ississsss',$this->ID_BOLETO, $this->TITULO ,$this->TIPO_EVENTO,$this->ID_LUGAR, $this->FECHA_INICIO, $this->FECHA_FIN, $this->ARTISTA, $this->ANTECEDENTES, $this->RESENA);
		}

		public function updateBind($stmt)
		{
			$stmt->bind_param('ississsssi',$this->ID_BOLETO ,$this->TITULO ,$this->TIPO_EVENTO, $this->ID_LUGAR, $this->FECHA_INICIO, $this->FECHA_FIN, $this->ARTISTA, $this->ANTECEDENTES, $this->RESENA, $this->ID_EVENTO);
		}

		public function getEventos()
		{
            // retornar la informacion de todos los eventos disponibles en 
            // las proximas semanas
			$sql = "SELECT EV.ID_EVENTO,EV.TITULO,EV.FECHA_INICIO,EV.FECHA_FIN,EV.RESENA, 
					PU.URL_IMAGEN FROM PUBLICIDAD as PU INNER JOIN EVENTO as EV ON EV.ID_EVENTO=PU.ID_EVENTO
 					WHERE PU.FECHA_INICIO<=CURDATE() AND PU.FECHA_FIN>=CURDATE()";
            
            $result = $this->consultar($sql);

            return $this->fetch($result);

		}

        public function getEventosBusqueda($titulo)
        {
            // retornar la informacion de todos los eventos disponibles en 
            // las proximas semanas
            $sql = "SELECT EV.ID_EVENTO,EV.TITULO,EV.FECHA_INICIO,EV.FECHA_FIN,EV.RESENA, 
                    PU.URL_IMAGEN FROM PUBLICIDAD as PU INNER JOIN EVENTO as EV ON EV.ID_EVENTO=PU.ID_EVENTO
                    WHERE PU.FECHA_INICIO<=CURDATE() AND PU.FECHA_FIN>=CURDATE() AND EV.TITULO=?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param('s',$titulo);
            
            $stmt->execute();        
            if ($stmt->errno) {
                die('Error al consultar el registro '. $stmt->error);
            }
            $result = $stmt->get_result();
            $stmt->close();   

            return $this->fetch($result);             


        }
        public function getInfoEvento($id)
        {
            $sql = 'SELECT EV.TITULO , PV.HORA_INICIO , PV.HORA_FIN ,BL.PRECIO , BL.NINO, BL.ESTUDIANTE, BL.VEJEZ, BL.ADULTO
                    FROM PARTIDA_EVENTO AS PV INNER JOIN EVENTO AS EV ON EV.ID_EVENTO=PV.ID_EVENTO
                    INNER JOIN BOLETO AS BL ON BL.ID_BOLETO=EV.ID_BOLETO WHERE PV.ID_EVENTO=?';
            
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

        public function getEventosUbicacion()
        {
            $sql = 'SELECT EV.ID_EVENTO, EV.TITULO, LG.NOMBRE ,EV.FECHA_INICIO ,EV.FECHA_FIN FROM EVENTO AS EV 
                    INNER JOIN LUGAR AS LG ON LG.ID_LUGAR=EV.ID_LUGAR';

            $result = $this->consultar($sql);

            return $this->fetch($result);
        }

		public function getBoleto($id)
		{
			$sql = 'SELECT BL.* FROM EVENTO AS EV INNER JOIN BOLETO AS BL
 					ON BL.ID_BOLETO=EV.ID_BOLETO WHERE ID_EVENTO=?';
			
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

        public function getActivos()
          {
            $sql = 'SELECT ID_EVENTO, TITULO FROM EVENTO WHERE FECHA_INICIO<=CURDATE() AND FECHA_FIN>=CURDATE()';
            $result = $this->consultar($sql);

            return $this->fetch($result);              
          }  

        public function buscarEvento($titulo)
        {
            // TODO : esta busqueda es bastante insegura dado que no se escapa
            // el post
            $sql = "SELECT EV.TITULO FROM EVENTO AS EV INNER JOIN PUBLICIDAD AS PU
            ON PU.ID_EVENTO=EV.ID_EVENTO
             WHERE TITULO LIKE '$titulo%' AND
             PU.FECHA_INICIO<=CURDATE() AND PU.FECHA_FIN>=CURDATE()";
            $result = $this->consultar($sql);

            return $result->fetch_array();
        }

        public function getVentas()
        {
            $sql = "SELECT EV.ID_EVENTO,EV.TITULO, PV.CANTIDAD FROM VENTA AS VT INNER JOIN PARTIDA_VENTA 
                    AS PV ON PV.FOLIO=VT.FOLIO INNER JOIN EVENTO AS EV ON VT.ID_EVENTO=EV.ID_EVENTO
                    WHERE EV.FECHA_INICIO<=DATE_SUB(CURDATE(),INTERVAL 1 WEEK) AND EV.FECHA_FIN>=CURDATE()";
            $result = $this->consultar($sql);

            return $result; 
        }

        public function getVentasPorDia($id)
        {
            $sql = "SELECT EV.ID_EVENTO,EV.TITULO, PV.CANTIDAD,VT.FECHA_VENTA FROM VENTA AS VT INNER JOIN PARTIDA_VENTA 
                AS PV ON PV.FOLIO=VT.FOLIO INNER JOIN EVENTO AS EV ON VT.ID_EVENTO=EV.ID_EVENTO WHERE EV.ID_EVENTO=? AND
                VT.FECHA_VENTA>=DATE_SUB(CURDATE(),INTERVAL 1 WEEK) ORDER BY VT.FECHA_VENTA";

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

        public function getDisponibilidad($id,$n_sala)
        {
            $sql = "SELECT PV.CANTIDAD,PE.CAPACIDAD FROM VENTA AS VT INNER JOIN PARTIDA_VENTA 
                    AS PV ON PV.FOLIO=VT.FOLIO INNER JOIN PARTIDA_EVENTO AS PE ON VT.ID_EVENTO=PE.ID_EVENTO
                    WHERE VT.ID_EVENTO=? AND VT.N_SALA=?";
            $stmt = $this->conexion->prepare($sql);

            $stmt->bind_param('ii',$id,$n_sala);
            
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