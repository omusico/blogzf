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
    '.'                      . PATH_SEPARATOR .
    get_include_path());
/**
 * Carga de clases que sean necesarias
 */
include "Zend/Loader.php";
Zend_Loader::registerAutoload();
/**
 * Configuramos el layout
 * Agregamos el layout de colorpaper
 */    
$options = array(  
	'layout' => 'admin/index',
	'layout' => 'colorpaper/colorpaper',
    'layoutPath' => 'layout/' );
Zend_Layout::startMvc( $options );
/**
 * Levantamos la configuracion del archivo config.default.ini
 */
$config = new Zend_Config_Ini('config/config.default.ini');
/**
 * Registramos de forma global la configuracion del sitio. 
 */
$registry = Zend_Registry::getInstance();
$registry->set( 'config_ini', $config );
/**
 * Setup controller
 */
$controller = Zend_Controller_Front::getInstance();
$controller->setParam( 'config', 'config.default.ini' )
    ->setControllerDirectory('../application/controller')
    ->throwExceptions(true);

$router = $controller->getRouter();
$route = new Zend_Controller_Router_Route_Regex(
    '^admin$',
    array( 'controller' => 'admin', 'action' => 'index'  ));
$router->addRoute('sitemap', $route);
    
$controller->dispatch();