<?php
class IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
    	/**
    	 * Traemo los datos del archivo de configuaracion
    	 */
    	$registry = Zend_Registry::getInstance();
		$config = $registry->get( 'config_ini' );
    	/**
         * Url basicas del sistema
         */
        $this->view->staticServer = $config->site->static->server;
        $this->view->appServer = $config->site->static->server;
     	/**
         * Agrego el titulo de la pagina
         */
        $this->view->headTitle()->append('Blog con Zend Framework');
        /**
         * Agrego los css para esta pagina
         */
        $this->view->headLink()
            ->appendStylesheet( $this->view->staticServer . 'layout/colorpaper/css/jd.gallery.css' )
            ->appendStylesheet( $this->view->staticServer . 'layout/colorpaper/css/pink.css' )
            ->appendStylesheet( $this->view->staticServer . 'layout/colorpaper/css/style.css' );
        /**
         * Agrego los js basicos - POr ahora ninguno
         */
        /*
         $this->view->headScript()
			->appendFile( $this->view->staticServer . '/js/mootools/mootools.js');
		 */
    }
    public function indexAction()
    {
        
    }
}