<?php
/**
 * Plugin para administrar los layout de nuestro sistema. 
 *
 */
class Blogzf_Plugins_View extends Zend_Controller_Plugin_Abstract
{
    protected $_viewRenderer;
    protected $_view;
          
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
         * Esto es un singleton de la vista para que no lo reinicie
         */
        $this->_viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $this->_viewRenderer->initView();
    	/**
    	 * Traemo los datos del archivo de configuaracion
    	 */
    	$config = Zend_Registry::getInstance()->get( 'config_ini' );
		$this->_view = $this->_viewRenderer->view;
		
    	/**
         * Url basicas del sistema
         */
        $this->_view->staticServer = $config->site->static->server;
        $this->_view->appServer = $config->site->static->server;
     	/**
         * Agrego el titulo de la pagina
         */
        $this->_view->headTitle()->append( $config->site->title );
        /**
         * Agrego los css para esta pagina que siempre va a ser el mismo. 
         * /layout/nombre_layout/style.css esto es para poder agregar muchos layout. Y no dependan
         * de la cantidad de css, si necesitamos separar en mas archivos. Podemos hacer un @import desde 
         * style.css
         * 
         * Tambien instanciamos Zend_Dojo para el admin
         */
        if ( $request->module == 'admin' ) {
            $layout = $config->site->layout->admin;
            /**
             * Agregamos los helpers de Dojo
             */
            $this->_view->addHelperPath( 'Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper' );
            /**
             * Ahora habilitamos Zend_Dojo en nuestra vista
             */
            Zend_Dojo::enableView( $this->_view );
            /**
             * Configuracion de Dojo
             */
            $this->_view->dojo()->setDjConfigOption( 'parseOnLoad', false );
            $this->_view->dojo()->setDjConfigOption( 'userPlainJson', true );
            Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
        } else {
            $layout = $config->site->layout->default;
        }
        $this->_view->headLink()
                ->appendStylesheet( $this->_view->staticServer . 
                	'layout/'.$layout.'/styles.css' );

        $response = $this->getResponse();
        $response->insert( 'sidebar', 
             $this->_view->action( 'rightcontent', 'sidebar', $request->module ));
        $response->insert( 'footer', 
             $this->_view->action( 'footer', 'sidebar', $request->module  ));
        $response->insert( 'menutop', 
             $this->_view->action( 'menutop','sidebar', $request->module ));
                
                
    }
    public function postDispatch (Zend_Controller_Request_Abstract $request)
    {
    }
    public function dispatchLoopShutdown ()
    {
    }
}