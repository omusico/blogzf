<?php
/**
 * Plugin para administrar los layout de nuestro sistema. 
 *
 */
class Blogzf_Plugins_Config extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup (Zend_Controller_Request_Abstract $request)
    {
    }
    public function routeShutdown (Zend_Controller_Request_Abstract $request)
    {
    }
    public function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    {
    }
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $localConfig = new Zend_Config_Ini('../config/' .'config.local.ini', NULL, true);
        $config = new Zend_Config_Ini( '../config/' . 'config.default.ini', NULL, true);
        $config->merge( $localConfig )->setReadOnly();
        /**
         * Permite registra de forma pública las instancias de estas variables
         * de configuración
         */
        Zend_Registry::set( 'config_ini', $config );
        Zend_Registry::set( 'base_path', realpath('.') );
        /**
     	 * Configuración Base de Datos
     	 */
        $dataBase = Zend_Db::factory(
            $config->database->db->adapter, 
            $config->database->db->config->toArray());
        Zend_Db_Table::setDefaultAdapter( $dataBase );
        Zend_Registry::set( 'dbAdapter', $dataBase);
    }
    public function postDispatch (Zend_Controller_Request_Abstract $request)
    {
    }
    public function dispatchLoopShutdown ()
    {
    }
    private function _dataBaseConfig ()
    {
    }
}