<?php
/**
 * Configuración inicial
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
date_default_timezone_set('America/Buenos_Aires');
/**
 * Definición de directorios
    OBS: ZendFramwork esta fuera de la aplicacion
 */
set_include_path( 
    '../'                    . PATH_SEPARATOR .
    '../library'             . PATH_SEPARATOR .
    '../application/model'   . PATH_SEPARATOR .
    '../application/views'   . PATH_SEPARATOR .
    '.'                               . PATH_SEPARATOR .
    get_include_path());
/**
 * Carga de clases que sean necesarias
 */
include "Zend/Loader.php";
Zend_Loader::registerAutoload();
/**
 * Setup controller
 */
$controller = Zend_Controller_Front::getInstance();
$controller->setParam( 'config', 'config.default.ini' )
    ->setParam( 'env', 'development' )
    ->setControllerDirectory('../application/controller')
    ->throwExceptions(true)
    ->dispatch();