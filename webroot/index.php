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
	'../application/admin'   . PATH_SEPARATOR .
	'../application/admin/views' . PATH_SEPARATOR .
    '.'                      . PATH_SEPARATOR .
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
    ->setControllerDirectory( array( 
    	'default'=> '../application/controller',
    	'admin'=> '../application/admin/controller'))
    ->throwExceptions(true);
/**
 * Ahora levantamos los plugins, esto mas adelante podemos hacerlo dinamico
 * Mas adelante veremos como 
 */   
$controller->registerPlugin( new Blogzf_Plugins_Config());
$controller->registerPlugin( new Blogzf_Plugins_Layout());
$controller->registerPlugin( new Blogzf_Plugins_View());
$controller->registerPlugin( new Blogzf_Plugins_Backoffice());
$controller->dispatch();