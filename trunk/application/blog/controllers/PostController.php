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
        //$paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        $this->view->paginator = $paginator;
    }
    
    public function readAction() {

      $url = $this->getRequest()->getParam('url');

      $post = new Post();
      $query = $post->select()
                       ->from('post',array('*','num_comments'=>new Zend_Db_Expr('COUNT(comment_id)'), 'month' => 'SUBSTRING(MONTHNAME(post_created_on),1,3)', 'day' => 'DAY(post_created_on)' ))
                       ->join('users','users.user_id=post.user_id','username')
                       ->joinleft('comment', 'comment.post_id=post.post_id')
                       ->group('comment.post_id')
                       ->where(sprintf("post.post_status = 1 AND post.post_url='%s'",$url))
                       ->limit(1)
                       ->setIntegrityCheck(false);       
      
      $post = $post->fetchRow($query);
      $this->view->post = $post;
      
      if ($post) {
         
          $categories = new Category();
          $query = $categories->select()
                      ->from('post_category')
                      ->join('category', 'category.category_id = post_category.category_id')
                      ->where(sprintf("post_id='%s'", $post->post_id ))
                      ->setIntegrityCheck(false);
         
          $this->view->categories = $categories->fetchAll($query);
  
          $tags = new Tag();
          $query = $tags->select()
                      ->from('post_tag')
                      ->join('tag', 'tag.tag_id = post_tag.tag_id','tag_word')
                      ->where(sprintf("post_id='%s'", $post->post_id ))
                      ->setIntegrityCheck(false);
          
          $this->view->tags = $tags->fetchAll($query);            
          
          $comments = new Comment();
          $query = $comments->select()
                       ->where(sprintf("post_id='%s'",$post->post_id)); 
      
          $this->view->comments = $comments->fetchAll($query);
      }
      
      $form = new forms_Comment();
      $this->view->form = $form;
      
    }

    function categoryAction() {
        $request = $this->getRequest();
        $name = $request->getParam('name');

        $category = new Category();
         
        $query = $category->select()
                       ->from('category', array('num_comments'=> new Zend_Db_Expr('COUNT(comment_id)'), 'month' => 'SUBSTRING(MONTHNAME(post_created_on),1,3)', 'day' => 'DAY(post_created_on)' ))
                       ->join('post_category','post_category.category_id=category.category_id', array())
                       ->join('post','post.post_id=post_category.post_id', array('*'))
                       ->join('users','users.user_id=post.user_id','username')
                       ->joinleft('comment', 'comment.post_id=post.post_id',array())
                       ->group('post.post_id')
                       ->where(sprintf("post_status = 1 AND category.category_url='%s'", $name))
                       ->order('post_created_on DESC')
                       ->setIntegrityCheck(false);
   
                       
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect( $query ));
        
        $paginator->setCurrentPageNumber( $this->_getParam( 'page', 1 ) );
        $this->view->paginator = $paginator;       
                
    }
    
    function tagAction() {
        $request = $this->getRequest();
        $name = $request->getParam('name');

        $tags = new Tag();
        
        $query = $tags->select()
                       ->from('tag', array('num_comments'=> new Zend_Db_Expr('COUNT(comment_id)'), 'month' => 'SUBSTRING(MONTHNAME(post_created_on),1,3)', 'day' => 'DAY(post_created_on)' ))
                       ->join('post_tag','post_tag.tag_id=tag.tag_id', array())
                       ->join('post','post.post_id=post_tag.post_id', array('*'))
                       ->join('users','users.user_id=post.user_id','username')
                       ->joinleft('comment', 'comment.post_id=post.post_id',array())
                       ->group('post.post_id')
                       ->where(sprintf("post_status = 1 AND tag.tag_url='%s'", $name))
                       ->order('post_created_on DESC')
                       ->setIntegrityCheck(false);
   
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect( $query ));
        
        $paginator->setCurrentPageNumber( $this->_getParam( 'page', 1 ) );
        $this->view->paginator = $paginator;       
 
    }

    function archiveAction() {
        
        $request = $this->getRequest();
        $year = $request->getParam('year');
        $month = $request->getParam('month');
                        
        $posts = new Post();
        
        $query = $posts->select()
                       ->from('post',array('*','num_comments'=> new Zend_Db_Expr('COUNT(comment_id)'), 'month' => 'SUBSTRING(MONTHNAME(post_created_on),1,3)', 'day' => 'DAY(post_created_on)' ))
                       ->join('users','users.user_id=post.user_id','username')
                       ->joinleft('comment', 'comment.post_id=post.post_id',array())
                       ->group('post.post_id')
                       ->where(sprintf("post_status = 1 and MONTH(post_created_on)='%s' and YEAR(post_created_on)='%s'",$month, $year ))
                       ->order('post_created_on DESC')
                       ->setIntegrityCheck(false);
           
                       
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        
        $paginator = new Zend_Paginator( new Zend_Paginator_Adapter_DbTableSelect( $query ));
        
        $paginator->setCurrentPageNumber( $this->_getParam( 'page', 1 ) );
        $this->view->paginator = $paginator;       
        
        $this->view->date = new Zend_Date(array('year' => $year,'month' => $month,'day' => 01));
        
    }
    
}
