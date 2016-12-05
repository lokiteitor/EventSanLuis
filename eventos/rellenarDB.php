<?php 

require_once 'vendor/fzaninotto/faker/src/autoload.php';
require_once 'php/database/Boleto.php';
require_once 'php/database/Espectacular.php';
require_once 'php/database/Evento.php';
require_once 'php/database/Lugar.php';
require_once 'php/database/Publicidad.php';
require_once 'php/database/Usuario.php';
require_once 'php/database/Venta.php';


require_once 'php/database/PartidaVenta.php';
require_once 'php/database/PartidaPublicidad.php';
require_once 'php/database/PartidaEvento.php';


$faker = Faker\Factory::create();


// rellenar la tabla usuarios
echo "Creando Usuarios\n";
for ($i=0; $i < 1000; $i++) { 
    
    $usuario = new Usuario();

    $usuario->NOMBRE = $faker->firstname();
    $usuario->APELLIDOS = $faker->lastName;
    $usuario->SEXO = $faker->randomElement(array('MASCULINO','FEMENINO'));
    $usuario->FECHA_NAC = $faker->date();
    $usuario->EMAIL = $faker->safeEmail;
    $usuario->TELEFONO = $faker->tollFreePhoneNumber;
    $usuario->DIRECCION = $faker->streetAddress;
    $usuario->RFC = $faker->swiftBicNumber;
    $usuario->USERNAME = $faker->unique()->userName;
    $usuario->PASSWORD = password_hash($faker->password,PASSWORD_DEFAULT);
    $usuario->ACTIVO  = $faker->boolean;

    $usuario->save();
}

echo "Creando Lugares\n";
for ($i=0; $i < 20; $i++) { 
    $lugar = new Lugar();
    $lugar->NOMBRE = $faker->company;
    $lugar->CALLE = $faker->streetName;
    $lugar->N_EXT = $faker->buildingNumber;
    $lugar->TELEFONO = $faker->tollFreePhoneNumber;
    $lugar->EMAIL = $faker->safeEmail;
    $lugar->LATITUD = $faker->latitude($min=22.157,$max=22.161);
    $lugar->LONGITUD = $faker->longitude($min=-101,$max=-100);
    $lugar->save();
}

echo "Creando Espectaculares\n";
for ($i=0; $i < 100; $i++) { 
    $espec = new Espectacular();
    $espec->UBICACION = $faker->address;
    $espec->LATITUD = $faker->latitude($min=22.157,$max=22.161);
    $espec->LONGITUD = $faker->longitude($min=-101,$max=-100);
    $espec->ZONA = $faker->randomElement(array('NORTE','SUR','ESTE','OESTE'));
    $espec->TIPO = $faker->randomElement(array('CARTEL','MAMPARA','ESPECTACULAR'));
    $espec->save();
}



for ($i=0; $i < 100; $i++) { 
    echo "Creando Evento " . $i.'\n';
    echo "boleto";
    $boleto = new Boleto();
    $boleto->PRECIO = $faker->randomFloat();
    $boleto->NINO = $faker->numberBetween(0,20);
    $boleto->ESTUDIANTE = $faker->numberBetween(0,20);
    $boleto->VEJEZ = $faker->numberBetween(0,20);
    $boleto->ADULTO = $faker->numberBetween(0,20);
    $boleto->save();

    echo "evento";
    $evento = new Evento();
    $evento->TITULO = $faker->text(35);
    $evento->TIPO_EVENTO = $faker->randomElement(array('TEATRO','CINE','EXP_ARTISTICA','MUSICAL'));
    $evento->ID_LUGAR = $faker->numberBetween(1,20);
    $inicio = $faker->dateTimeBetween($startDate = '-2 weeks',$endDate = '+3 weeks');
    $fin = $faker->dateTimeBetween($startDate = '+3 weeks',$endDate = '+6 weeks');
    $evento->FECHA_INICIO = $inicio->format('Y-m-d');
    $evento->FECHA_FIN = $fin->format('Y-m-d');
    $evento->ARTISTA = $faker->name();
    $evento->ANTECEDENTES = $faker->text();
    $evento->RESENA = $faker->text();
    $evento->ID_BOLETO = $boleto->ID;
    $evento->save();
    echo "publicidad";
    $publicidad = new Publicidad();
    $publicidad->ID_EVENTO = $evento->ID;
    $publicidad->FECHA_INICIO = $evento->FECHA_INICIO;
    $publicidad->FECHA_FIN = $evento->FECHA_FIN;
    $publicidad->URL_IMAGEN = $faker->file($sourceDir='storage/',$targetDir='storage/img');
    $publicidad->save();

    echo "partida publicidad";
    for ($j=1; $j < 3; $j++) { 
        $regPub = new PartidaPublicidad();
        $regPub->ID_PUBLICIDAD = $publicidad->ID;
        $regPub->ID_ESPECTACULAR = $faker->unique()->numberBetween(1,100);
        $regPub->ACTIVO = $faker->boolean;
        $regPub->savePartida(false);            
    }
    $faker->unique(true)->numberBetween(1,100);
    echo "partidad evento";
    for ($j=1; $j < 5; $j++) { 
        $pevento = new PartidaEvento();
        $pevento->ID_EVENTO = $evento->ID;
        $pevento->N_SALA = $faker->unique()->numberBetween(1,10);
        $pevento->HORA_INICIO = $faker->time();
        $pevento->HORA_FIN = $faker->time();
        $pevento->CAPACIDAD = $faker->numberBetween(100,1000);
        $pevento->savePartida(false);        
    }
    $faker->unique(true)->numberBetween(1,100);
    echo "\n";

}

echo "Creando Ventas";
for ($i=0; $i < 1000; $i++) { 
    $venta = new Venta();
    $venta->ID_USUARIO = $faker->numberBetween(1,1000);
    $venta->ID_EVENTO = $faker->numberBetween(1,100);
    $venta->N_SALA = $faker->numberBetween(1,10);
    $fecha = $faker->dateTimeBetween($startDate = '-1 weeks',$endDate = '+2 weeks');
    $venta->FECHA_VENTA = $fecha->format('Y-m-d');
    $venta->save();

    for ($x=1; $x < 5; $x++) { 
        $pventa = new PartidaVenta();
        $pventa->FOLIO = $venta->ID;
        $pventa->N_REGISTRO = $x;
        $pventa->ID_BOLETO = $venta->ID_EVENTO;
        $pventa->CANTIDAD = $faker->numberBetween(1,10);
        $pventa->TIPO = $faker->randomElement(array('nino','adulto','vejez','estudiante'));
        $pventa->savePartida(false);                
    }

}



 ?>