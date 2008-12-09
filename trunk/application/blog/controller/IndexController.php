<?php
class Blog_IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
    }
    public function indexAction()
    {
        /**
		 * Configuramos el paginador, para que me traiga todos los resultados paginados.
		 */
		Zend_Paginator::setDefaultScrollingStyle( 'all' );
		/**
		 * Este es el tpl que voy a usar como paginador
		 */
        $posts = new Posts();
        $query = $posts->select()->where('status = \'publicado\'')->order('created_date DESC');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbSelect( $query ));
        $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
    }
}
