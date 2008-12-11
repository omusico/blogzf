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
    '../application/blog/views'   . PATH_SEPARATOR .
	'../application/admin'   . PATH_SEPARATOR .
	'../application/admin/views' . PATH_SEPARATOR .
    '.'                      . PATH_SEPARATOR .
    get_include_path());
/**
 * Carga de clases que sean necesarias
 */
include "Zend/Loader.php";
Zend_Loader::registerAutoload();

$request = new Zend_Controller_Request_Http();
$response = new Zend_Controller_Response_Http();
$response->setHeader('Content-type','text/html; charset=utf-8');


/**
 * Setup controller
 */
$controller = Zend_Controller_Front::getInstance();
$controller->setParam( 'config', 'config.default.ini' );

$dirs = new DirectoryIterator('../application/');

foreach ($dirs as $dir) {
    if ($dir->isDir() && !in_array($dir,  array('model','.','..')) ) {
        $controller->addControllerDirectory('../application/' . $dir->getFilename() .'/controllers', $dir->getFilename());
    }
}


$router = $controller->getRouter();
  
/*
if (file_exists("../application/routes.xml")) {
   $routes = new Zend_Config_Xml("../application/routes.xml", 'routes');

   if ($routes->route) {
    foreach ($routes as $route) {
        $router->addRoute($route->name,  new Zend_Controller_Router_Route($route->url, $route->params->toArray() ));
    }
   }
}
*/

//$router->addRoute('default', new Zend_Controller_Router_Route(':controller/:action', array('module' => 'blog') ));

$controller->throwExceptions(true);


$controller->registerPlugin( new Blogzf_Plugins_Config());
$controller->registerPlugin( new Blogzf_Plugins_Layout());
$controller->registerPlugin( new Blogzf_Plugins_View());
$controller->registerPlugin( new Blogzf_Plugins_Backoffice());
$controller->dispatch($request, $response);


