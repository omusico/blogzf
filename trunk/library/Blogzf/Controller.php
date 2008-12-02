<?php
class Blogzf_Controller extends Zend_Controller_Action
{
    protected $_registry;
    protected $_user;
    protected $_roles;
    protected $_config;
    protected $_db;
    public function getDb ()
    {
        return $this->_db;
    }
    public function getConfig ()
    {
        return $this->_config;
    }
    public function getUser ()
    {
        return $this->_user;
    }
    public function init ()
    {
    }
    public function preDispatch ()
    {
        /**
         * Configuracion de base de datos, archivos config y otros
         */
        $this->_config = new Blogzf_Config();
        $this->_config->load();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $this->_registry = Zend_Registry::getInstance();
        $this->layoutConfig();        
    	/**
    	 * Traemo los datos del archivo de configuaracion
    	 */
		$config = $this->_registry->get( 'config_ini' );
    	/**
         * Url basicas del sistema
         */
        $this->view->staticServer = $config->site->static->server;
        $this->view->appServer = $config->site->static->server;
     	/**
         * Agrego el titulo de la pagina
         */
        $this->view->headTitle()->append('Administracion de BlogZf');
        /**
         * Agrego los css para esta pagina
         */
        $this->view->headLink()
            ->appendStylesheet( $this->view->staticServer . 'layout/admin/css/login.css' )
            ->appendStylesheet( $this->view->staticServer . 'layout/admin/css/colors-fresh.css' );
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
    /**
     * Configuramos los layout que tiene el sistema
     */
    private function layoutConfig ()
    {
        $options = array('layout' => 'Cart' , 'layout' => 'Frontend' , 'layoutPath' => '../layout/');
        Zend_Layout::startMvc($options);
    }
}