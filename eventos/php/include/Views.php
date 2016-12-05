<?php 

/**
 * @file Views.php
 * @brief: Este archivo gestiona la generacion de las vistas que es la parte 
 * de html que se ve en el sitio web
 */


require_once 'config.php';

/**
 * @brief Renderiza una vista en el navegador
 * @param $name -- nombre de la vista sin la extencion del archivo
 */
function View($name)
{
    require_once 'php/include/Url.php';
    require_once 'php/include/Views.php';
    // buscar la vista en el directorio correspondiente
    global $site;
    if (file_exists($site['views'] . $name.'.php')) {
        // dibujar
        include $site['views'] . $name .'.php';
    }
}


/**
 * @brief Buscar el layout he imprimirlo
 * @return void
 */
function Layout($layout)
{ 
    require_once 'php/include/Url.php';
    require_once 'php/include/Views.php';
    global $site;
    if (file_exists($site['layouts'].'/'.$layout.'.php' )) {
        include $site['layouts'].'/'.$layout.'.php';
    }    
}


/**
 * @brief Obtiene el nombre de la ruta actualmente solicitada

 * @return ruta a la pagina actual
 */
function getRuta()
{
    return $_SERVER['REQUEST_URI'];
}


 ?>
