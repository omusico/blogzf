<?php
class Blog_CommentController extends Zend_Controller_Action
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
        $comments = new Comment();
        $query = $posts->select();
        
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect( $query ));
        $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
    }
    

}
