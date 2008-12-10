<?php
class IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
    }
    public function indexAction()
    {
        /*
        $this->_helper->actionStack('menutop','sidebar');  
        $this->_helper->actionStack('rightcontent','sidebar');  
        $this->_helper->actionStack('footer','sidebar');  
        */
        
        /**
		 * Configuramos el paginador, para que me traiga todos los resultados paginados.
		 */
		Zend_Paginator::setDefaultScrollingStyle( 'all' );
		/**
		 * Este es el tpl que voy a usar como paginador
		 */
        $posts = new Post();
        $query = $posts->select()->where('post_status = 1')->order('post_created_on DESC');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect( $query ));
        $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
    }
    

}
