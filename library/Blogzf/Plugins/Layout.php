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
        $config = Zend_Registry::getInstance()->get( 'config_ini' );
        $layout = ( $request->getParam('module') == 'admin' )
                ? $config->site->layout->admin
                : $config->site->layout->default;
        $options = array(  
        	'layout' => $layout . '/index',
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