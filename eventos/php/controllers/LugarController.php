<?php 
require_once 'php/database/Lugar.php';


/**
* 
*/
class LugarController
{
    public function registrar()
        {
            $lugar = new Lugar();
            if (isset($_POST['nombre'])) {
                $lugar->NOMBRE = $_POST['nombre'];
            }

            if (isset($_POST['calle'])) {
                $lugar->CALLE = $_POST['calle'];
            }

            if (isset($_POST['numero'])) {
                $lugar->N_EXT = $_POST['numero'];
            }

            if (isset($_POST['telefono'])) {
                $lugar->TELEFONO = $_POST['telefono'];
            }

            if (isset($_POST['email'])) {
                $lugar->EMAIL = $_POST['email'];
            }

            if (isset($_POST['latitud'])) {
                $lugar->LATITUD = $_POST['latitud'];
            }
            if (isset($_POST['longitud'])) {
                $lugar->LONGITUD = $_POST['longitud'];
            }

            $lugar->save();
    }    
}


 ?>