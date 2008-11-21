<?php
class IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        /**
         * Url basicas del sistema
         */
        $this->view->staticServer = 'http://blogzf.dev:8001/';
        $this->view->appServer = 'http://blogzf.dev:8001/';
                
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
