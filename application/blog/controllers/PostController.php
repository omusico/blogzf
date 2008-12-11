<?php
class Blog_PostController extends Zend_Controller_Action
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
        $query = $posts->select()
                       ->from('post',array('*','num_comments'=>new Zend_Db_Expr('COUNT(comment_id)'), 'month' => 'SUBSTRING(MONTHNAME(post_created_on),1,3)', 'day' => 'DAY(post_created_on)' ))
                       ->join('users','users.user_id=post.user_id','username')
                       ->joinleft('comment', 'comment.post_id=post.post_id')
                       ->group('comment.post_id')
                       ->where('post_status = 1')
                       ->order('post_created_on DESC')
                       ->setIntegrityCheck(false);
                       
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect( $query ));
        $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
    }
    

}
