<?php
/**
 * Plugin para administrar los layout de nuestro sistema. 
 *
 */
class Blogzf_Controller_Plugin_View extends Zend_Controller_Plugin_Abstract
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
    	$auth = Zend_Auth::getInstance();
    	
		$this->_view = $this->_viewRenderer->view;
		
		/**
		 * Agregamos unas 
		 */
        $this->_view->baseUrl = $request->getBaseUrl();
        $this->_view->module = $request->getModuleName();
        $this->_view->controller = $request->getControllerName();
        $this->_view->action = $request->getActionName();

        $this->_view->hasIdentity = false;
        
        if ( $auth->hasIdentity() ) {
            $this->_view->hasIdentity = true;
            $this->_view->Identity = $auth->getIdentity();
        }
        
		/**
		 * Agregamos las rutas para las vistas
		 */
		$this->_view->addScriptPath('/application/blog/views');
		$this->_view->addScriptPath('/application/admin/views');
    	/**
         * Url basicas del sistema
         */
        $this->_view->staticServer = $config->site->static->server;
        $this->_view->appServer = $config->site->static->server;
     	/**
         * Agrego el titulo de la pagina
         */
        $this->_view->headTitle()->append( $config->site->title );
        $this->_view->site = $config->site;
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
            $this->_view->addHelperPath( 'Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper' );
            Zend_Dojo::enableView($this->_view);
            $this->_view->dojo()->setDjConfigOption( 'parseOnLoad', false );
            $this->_view->dojo()->setDjConfigOption( 'userPlainJson', true );
            Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
        } else {
            $layout = $config->site->layout->default;
        }
        $this->_view->headLink()
                ->appendStylesheet( $this->_view->staticServer . 
                	'layout/'.$layout.'/styles.css' );


    }
    public function postDispatch (Zend_Controller_Request_Abstract $request)
    {
        if ($this->_view->module=='default') {
            return;
        }
                
        if ($this->_view->layout()->isEnabled() ) {
            $this->_view->layout()->sidebar = $this->_view->action( 'rightcontent', 'sidebar', $this->_view->module );
            $this->_view->layout()->footer = $this->_view->action( 'footer', 'sidebar', $this->_view->module );
            $this->_view->layout()->menutop = $this->_view->action( 'menutop','sidebar', $this->_view->module );
	    }     
    }
    public function dispatchLoopShutdown ()
    {
    }
}
