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

$router = $controller->getRouter();
 
$router->addRoute('blog-archive',  new Zend_Controller_Router_Route('archives/:year/:month', array('module'=>'blog', 'controller'=>'post', 'action' => 'archive' ) ));       
$router->addRoute('blog-category',  new Zend_Controller_Router_Route('category/:name', array('module'=>'blog', 'controller'=>'post', 'action' => 'category' ) ));       
$router->addRoute('blog-tag',  new Zend_Controller_Router_Route('tag/:name', array('module'=>'blog', 'controller'=>'post', 'action' => 'tag' ) ));       
$router->addRoute('blog-read',  new Zend_Controller_Router_Route('read/:url', array('module'=>'blog', 'controller'=>'post', 'action' => 'read' ) ));        
$router->addRoute('blog-page',  new Zend_Controller_Router_Route('page/:name', array('module'=>'blog', 'controller'=>'page', 'action' => 'index' ) ));       
$router->addRoute('blog-home',  new Zend_Controller_Router_Route('posts', array('module'=>'blog', 'controller'=>'post', 'action' => 'index' ) ));   

$dirs = new DirectoryIterator('../application/');

foreach ($dirs as $dir) {
    if ($dir->isDir() && !in_array($dir,  array('model','.','..')) ) {
        $controller->addControllerDirectory(
        	'../application/' . $dir->getFilename() .'/controllers', $dir->getFilename());
    }
}


$controller->throwExceptions(true);

$controller->registerPlugin( new Blogzf_Plugins_Config());
//$controller->registerPlugin( new Blogzf_Plugins_Routes());
$controller->registerPlugin( new Blogzf_Plugins_Layout());
$controller->registerPlugin( new Blogzf_Plugins_View());
$controller->registerPlugin( new Blogzf_Plugins_Backoffice());
$controller->dispatch($request, $response);

