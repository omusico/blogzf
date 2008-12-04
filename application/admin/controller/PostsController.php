<?php
class Admin_PostsController extends Zend_Controller_Action 
{
	public function createAction()
	{
        $form = new forms_Posts();
	    if ( $this->_request->isPost() ) {
		    $postData = $this->_request->getPost();
		    if ( $form->isValid( $postData ) ) {
		        try {
                    $posts = new Posts();
                    $post = $posts->createRow();
                    $post->title = $postData['title'];
                    $post->content = $postData['content'];
                    $post->user_id = $postData['user_id'];
                    $post->comment = $postData['comment'];
                    $post->created_date = new Zend_Db_Expr('now()');
                    $post->status = $postData['status'];
    		        $post->save();
    		        $this->_redirect( '/admin/posts/read/' );
		        } catch( Zend_Exception $e ) {
		            echo "Caught exception: " . get_class( $e ) . "\n";
                    echo "Message: " . $e->getMessage() . "\n";
		        }
		    } else {
		        $form->populate( $postsData );
		    }
	    }
	    $this->view->form = $form;
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