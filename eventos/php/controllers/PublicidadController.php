<?php 
require_once 'php/database/Espectacular.php';
require_once 'php/database/Publicidad.php';
require_once 'php/include/config.php';
/**
* 
*/
class PublicidadController
{
    public function registrarEspectacular()
    {
        // crear un registro de espectacular
        $espectacular = new Espectacular();
        if (isset($_POST['latitud'])) {
            $espectacular->LATITUD = $_POST['latitud'];
        }

        if (isset($_POST['longitud'])) {
            $espectacular->LONGITUD = $_POST['longitud'];
        }

        if (isset($_POST['zona'])) {
            $espectacular->ZONA = $_POST['zona'];
        }

        if (isset($_POST['tipo'])) {
            $espectacular->TIPO = $_POST['tipo'];
        }

        if (isset($_POST['ubicacion'])) {
            $espectacular->UBICACION = $_POST['ubicacion'];
        }

        $espectacular->save();

    }

    public function registrarPublicidad()
    {
        // crear el registro de una publicidad
        global $site;
        $publicidad = new Publicidad();

        if (isset($_POST['inicio'])) {
            $publicidad->FECHA_INICIO = $_POST['inicio'];
        }

        if (isset($_POST['fin'])) {
            $publicidad->FECHA_FIN = $_POST['fin'];
        }

        if (isset($_POST['id_evento'])) {
            $publicidad->ID_EVENTO = $_POST['id_evento'];
        }

        // subir la imagen al servidor

        // renombrar el archivo
        $ext = pathinfo($_FILES['imagen']['name'],PATHINFO_EXTENSION);
        $dest = $site['storage']. md5(basename($_FILES['imagen']['name']) .time() ) .'.'. $ext;

        $publicidad->URL_IMAGEN = $dest;

        move_uploaded_file($_FILES['imagen']['tmp_name'], $dest);


        $publicidad->save();


    }
}

 ?>