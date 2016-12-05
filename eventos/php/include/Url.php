<?php 
require_once 'config.php';
/**
 * @file Url.php
 * @brief Esta parte del framework se encarga de resolver las rutas a los recurso
 * esto con el fin de resolver el problema de links rotos al trabajar sobre un
 * subdirectorio de root
 */

/**
 * @brief devuelve la url a una pagina del sitio web
 * @param $path ruta que se desea resolver
 * @return imprimi la ruta con respecto al directorio en el que se ubica
 */
function url($path)
{
    // devuelve la url a una pagina del sitio web
    global $site;
    echo $site['root']. $path;
}

/**
 * @brief resuelve una ruta con respecto al directorio con el que se ubica
 * @param $path , ruta a resolver
 * @return retorna un string con la ruta resuelta
 */
function getUrl($path)
{
    // devuelve la url a una pagina del sitio web
    global $site;
    return $site['root']. $path;
}


/**
 * @brief resuelve la ruta hacia la libreria especificada
 * @param ruta de la libreria a resolver
 * @return imprime la url a la libreria
 */
function asset($path)
{
    // devuelve la ruta al recurso
    // entiendase por asset, todas las imagenes ,css y js

    //tomar el directorio en el que se encuentra y concatenarlo al pedido
    global $site;
    echo $site['root'] . $path;
}

 ?>