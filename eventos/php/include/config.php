<?php 	

    /**
     * @file config.php 
     * @brief este archivo sirve para mantener la configuracion 
     * centralizada , esta configuracion incluye la conexion a la base de datos
     * asi como las rutas de interes para el motor
     */

	// Aqui se debe almacenar las variables de configuracion
	$db = array(
			'host' => '172.18.0.2',
			'user' => 'root',
			'password' => 'usbw',
			'dbname' => 'EVENTOS'
		);

    $site = array(
        'url' => 'http://localhost/eventos',
        'views' => 'php/views/',
        'layouts' => 'php/views/layout',
        'root' => '/eventos',
        'storage' => 'storage/'
        );

 ?>
