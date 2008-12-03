<?php
/**
 * Plugin para administrar los layout de nuestro sistema. 
 *
 */
class Blogzf_Plugins_Layout extends Zend_Controller_Plugin_Abstract
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
        /**
         * Configuramos el layout
         * Aca podriamos llamar a una tabla y obtener el layout activo
         * Por ahora vamos a usar los dos por defecto
         */    
        $options = array(  
        	'layout' => 'admin/index',
        	'layout' => 'colorpaper/colorpaper',
            'layoutPath' => 'layout/' );
        Zend_Layout::startMvc( $options );        
    }
    public function postDispatch (Zend_Controller_Request_Abstract $request)
    {
    }
    public function dispatchLoopShutdown ()
    {
    }
}