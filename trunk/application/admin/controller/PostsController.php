<?php
class Admin_PostsController extends Zend_Controller_Action 
{
	public function createAction()
	{
        $form = new forms_Posts();
	    if ( $this->_request->isPost() ) {
		    $postsData = $this->_request->getPost();
		    if ( $form->isValid( $postsData ) ) {
		        unset( $postsData['submit']);
		        $posts = new Posts();
		        $postsData['password'] = md5( $postsData['password'] );
		        $postsId = $posts->add( $postsData );
		        $this->_redirect( '/posts/read/' );
		    } else {
		        $form->populate( $postsData );
		    }
	    }
	    $this->form = $form;
	}
	public function readAction()
	{
		/**
		 * Configuramos el paginador, para que me traiga todos los resultados paginados.
		 */
		Zend_Paginator::setDefaultScrollingStyle( 'all' );
		/**
		 * Este es el tpl que voy a usar como paginador
		 */
        $posts = new Posts();
        $query = $posts->select();
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbSelect( $query ));
        $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
	}
	public function updateAction()
	{
		
	}
	public function deleteAction()
	{
		
	}
}