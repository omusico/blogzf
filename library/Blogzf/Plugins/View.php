<?php
/**
 * Plugin para administrar los layout de nuestro sistema. 
 *
 */
class Blogzf_Plugins_View extends Zend_Controller_Plugin_Abstract
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
    	 * Traemo los datos del archivo de configuaracion
    	 */
    	$config = Zend_Registry::getInstance()->get( 'config_ini' );
		$this->view = new Zend_View();
    	/**
         * Url basicas del sistema
         */
        $this->view->staticServer = $config->site->static->server;
        $this->view->appServer = $config->site->static->server;
     	/**
         * Agrego el titulo de la pagina
         */
        $this->view->headTitle()->append( $config->site->title );
        /**
         * Agrego los css para esta pagina que siempre va a ser el mismo. 
         * /layout/nombre_layout/style.css esto es para poder agregar muchos layout. Y no dependan
         * de la cantidad de css, si necesitamos separar en mas archivos. Podemos hacer un @import desde 
         * style.css
         */
        
        $layout = ( $request->module == 'admin' )
                ? $config->site->layout->admin 
                : $config->site->layout->default;
        $this->view->headLink()
                ->appendStylesheet( $this->view->staticServer . 
                	'layout/'.$layout.'/styles.css' );
        /**
         * Agrego los js basicos - POr ahora ninguno
         */
        /*
         $this->view->headScript()
			->appendFile( $this->view->staticServer . '/js/mootools/mootools.js');
		 */
         /**
          * Asignamos a las diferentes vistas, su modulo correspondiente.
          */
         $response = $this->getResponse();
         $response->insert( 'sidebar', $this->view->action( 'rightcontent', 'sidebar' ));
         $response->insert( 'footer', $this->view->action( 'footer', 'sidebar' ));
         $response->insert( 'topMenu', $this->view->action( 'menutop','sidebar' ));
    }
    public function postDispatch (Zend_Controller_Request_Abstract $request)
    {
    }
    public function dispatchLoopShutdown ()
    {
    }
}