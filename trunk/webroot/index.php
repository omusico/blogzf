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

$localConfig = new Zend_Config_Ini('../config/' .'config.local.ini', NULL, true);
$config = new Zend_Config_Ini( '../config/' . 'config.default.ini', NULL, true);
$config->merge( $localConfig )->setReadOnly();

Zend_Registry::set( 'config_ini', $config );
Zend_Registry::set( 'base_path', realpath('.') );
        
$dataBase = Zend_Db::factory(
$config->database->db->adapter, 
$config->database->db->config->toArray());

$dataBase->query("SET NAMES 'utf8'");
$dataBase->query("SET lc_time_names = 'es_ES'");

Zend_Db_Table::setDefaultAdapter( $dataBase );
Zend_Registry::set( 'dbAdapter', $dataBase);


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

$router->addRoute('archive',  new Zend_Controller_Router_Route('archive/:year/:month', array('module'=>'blog', 'controller'=>'post', 'action' => 'archive' ) ));       
$router->addRoute('category',  new Zend_Controller_Router_Route('category/:name', array('module'=>'blog', 'controller'=>'post', 'action' => 'category' ) ));       
$router->addRoute('tag',  new Zend_Controller_Router_Route('tag/:name', array('module'=>'blog', 'controller'=>'post', 'action' => 'tag' ) ));       
$router->addRoute('read',  new Zend_Controller_Router_Route(':url', array('module'=>'blog', 'controller'=>'post', 'action' => 'read' ) ));
$router->addRoute('page',  new Zend_Controller_Router_Route('page/:name', array('module'=>'blog', 'controller'=>'page', 'action' => 'index' ) ));       
$router->addRoute('default',  new Zend_Controller_Router_Route('', array('module'=>'blog', 'controller'=>'post', 'action' => 'index' ) ));       


$controller->throwExceptions(true);

$controller->registerPlugin( new Blogzf_Plugins_Layout());
$controller->registerPlugin( new Blogzf_Plugins_View());
$controller->registerPlugin( new Blogzf_Plugins_Backoffice());
$controller->dispatch($request, $response);


