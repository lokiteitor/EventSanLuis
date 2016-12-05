<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>EventSanLuis</title>
        <?php Layout('libs'); ?>
    </head>
    <body>
        <?php Layout('nav'); ?>
        
        <div id="carousel-id" class="carousel slide hidden-sm hidden-xs" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carousel-id" data-slide-to="0" class=""></li>
                <li data-target="#carousel-id" data-slide-to="1" class=""></li>
                <li data-target="#carousel-id" data-slide-to="2" class="active"></li>
            </ol>
            <div class="carousel-inner">
                <!-- Crear un item por cada elemento en el slider -->
                <div class="item">
                    <img src=<?php asset('/img/slider1.jpg') ?>>
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Consulta y compra en línea</h1>
                            <p>Facilidad de búsqueda y compra</p>
                        </div>
                    </div>
                </div>
                <!-- Termina item -->
                <div class="item">
                    <img src=<?php asset('/img/slider2.jpg') ?>>
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Los mejores eventos en la Capital de San Luis POtosí</h1>
                            <p>Siempre lo mas actual</p>
                        </div>
                    </div>
                </div>
                <div class="item active">
                    <img src=<?php asset('/img/slider3.jpg') ?>>
                    <div class="carousel-caption">
                        <h1>Entrega de boletos hasta la puerta de tu domicilio</h1>
                        <p>Registrate y adquiere el mejor servicio </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid" id="main">
        <!-- En esta seccion van los objetivos de la pagina -->
        <div class="row objetivos">
            <div class="col-lg-4 col-md-4 text-center">
                <img src=<?php asset('/img/icon1.png') ?> class="img-rounded objetivos" >
                <h2>Objetivo General</h2>
                
                <p>
                Ofrecemos el servicio de difundisión y promoción de eventos culturales 
                en el estado de San Luis Potosí , S.L.P. ; así como también la venta de boletos para
                asistir a éstos. de una manera fácil y sencilla.
                </p>

                
            </div>
            <div class="col-lg-4 col-md-4 text-center">
                <img src=<?php asset('/img/icon2.png') ?> class="img-rounded objetivos" >
                <h2>¿A quién vá dirigido éste sitio web?</h2>
                <p>A todo tipo de personas ya sean locales ,turistas nacionales e internacionales que deseen
                 y gusten por un momento recreativo y cultural en la Cd. de San Luis Potosí.
                </p>
                
            </div>
            <div class="col-lg-4 col-md-4 text-center">
                <img src=<?php asset('/img/icon3.png') ?> class="img-rounded objetivos" >
                <h2>Alcance</h2>
                <p>
                Concentrar los eventos mas destacadaos y por desarrollarse en la Capital,
                siempre lo mas actual de manera rápida y sencilla.
                
                </p>
                
            </div>
        </div>
        <!-- Terminan objetivos -->
        
        <!-- Con cada uno de estos se crea una seccion para mostrar las ventajas
        de nuestro sitio (img derecha)-->
        
        <hr>
        <div class="row text-center">
            <div class="  col-md-7 col-lg-7">
                <h2>Aquiere tus entradas a ese evento que tanto anhelaste asistir</h2>
                <p>Selecciona tu evento favorito y cómpralo en línea ,
                   brindamos la mayor seguridad en dicho trámite</p>
            </div>
            <div class="  col-md-5 col-lg-5">
                <img src=<?php asset('/img/icono4.png') ?> class="img-responsive ventajas" >
            </div>
        </div>
        <!-- Termina seccion -->
        <!-- (img izquierda) -->
        
        <hr>
        <div class="row text-center">
            <div class="  col-md-5 col-lg-5">
                <img src=<?php asset('/img/icon5.png') ?> class="img-responsive ventajas" >
            </div>
            <div class="  col-md-7 col-lg-7">
                <h2>filtra y consulta el evento que te interese</h2>
                <p>Contamos con la mayor gama de eventos culturales y recreativos
                 
                </p>
            </div>
        </div>
        <!-- termina img izq -->
        
        <hr>
        <div class="row text-center">
            <div class="  col-md-7 col-lg-7">
                <h2>Regístrate ,compra y adquiere promociones </h2>
                <p>¡¡¡Los beneficios de comprar en línea, y de manera segura!!!<p>
            </div>
            <div class="  col-md-5 col-lg-5">
                <img src=<?php asset('/img/icon6.png') ?> class="img-responsive ventajas" >
            </div>
        </div>
        
        <hr>
        <div class="row text-center">
            <div class="  col-md-5 col-lg-5">
                <img src=<?php asset('/img/icon7.png') ?> class="img-responsive ventajas" >
            </div>
            <div class="  col-md-7 col-lg-7">
                <h2>Atención a clientes personalizada</h2>
                <p>Si necestitas aclarar alguna duda ,contáctanos y la resolveremos</p>
            </div>
        </div>
    </div>
    <footer class="text-center container" >
        <!-- En esta seccion va el pie de pagina -->
        <!-- TODO : mover esto despues a un layout ya que se repite en todas las paginas -->
        <p>[EVENT SAN LUIS 2016] PROYECTO FINAL PARA LA MATERIA DE PROGRAMACIÓN II [U.P.S.L.P]</p>
        <p>[INTEGRANTES DEL PROYECTO: David Delgado ,Karen Martínez,Israel Jasso] </p>
    </footer>
</body>
</html>