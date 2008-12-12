<?php
class Blog_PostController extends Zend_Controller_Action
{
    public function init() 
    {
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addContext('rss' , array('suffix' => 'rss', 'headers' => array('Content-type' => 'application/rss+xml')  ));
        $contextSwitch->addContext('atom' , array('suffix' => 'atom', 'headers'=> array('Content-type' => 'application/atom+xml')  ));
        $contextSwitch->addActionContext('read', array( 'atom', 'rss') );
        $contextSwitch->addActionContext('index', array( 'atom', 'rss') );
        $contextSwitch->initContext();
        $contextSwitch->setAutoJsonSerialization(false);
        $this->_currentContext = $contextSwitch->getCurrentContext();

    }
    public function preDispatch ()
    {}
    public function indexAction ()
    {
        /**
         * Configuramos el paginador, para que me traiga todos los resultados paginados.
         */
        Zend_Paginator::setDefaultScrollingStyle('all');
        /**
         * Este es el tpl que voy a usar como paginador
         */
        $posts = new Post();
        $query = $posts->select()->from('post', array('*' , 'num_comments' => new Zend_Db_Expr('COUNT(comment_id)') , 'month' => 'SUBSTRING(MONTHNAME(post.created_on),1,3)' , 'day' => 'DAY(post.created_on)'))->join('users', 'users.user_id=post.user_id', 'username')->joinleft('comment', 'comment.post_id=post.post_id')->group('comment.post_id')->where('post.status = 1')->order('post.created_on DESC')->setIntegrityCheck(false);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        
        switch ($this->_currentContext) {
            case 'rss':
            case 'atom':
               $this->_helper->layout->disableLayout();
               $paginator = $posts->fetchAll();
               break;
            default:
               $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
               $paginator->setCurrentPageNumber( $this->_getParam( 'page' ) );
        }
  
        $this->view->paginator = $paginator;
        
        
    }
    public function readAction ()
    {
        $request = $this->getRequest();
        $url = $request->getParam('url');
        $post = new Post();
        $query = $post->select()->from('post', array('*' , 'num_comments' => new Zend_Db_Expr('COUNT(comment_id)') , 'month' => 'SUBSTRING(MONTHNAME(post.created_on),1,3)' , 'day' => 'DAY(post.created_on)'))->join('users', 'users.user_id=post.user_id', 'username')->joinleft('comment', 'comment.post_id=post.post_id',array())->group('post.post_id')->where(sprintf("post.status = 1 AND post.url='%s'", $url))->limit(1)->setIntegrityCheck(false);
        $post = $post->fetchRow($query);
        $this->view->post = $post;
        
        if ($post) {
            $categories = new Category();
            $query = $categories->select()->from('post_category')->join('category', 'category.category_id = post_category.category_id')->where(sprintf("post_id='%s'", $post->post_id))->setIntegrityCheck(false);
            $this->view->categories = $categories->fetchAll($query);
            $tags = new Tag();
            $query = $tags->select()->from('post_tag')->join('tag', 'tag.tag_id = post_tag.tag_id', 'word')->where(sprintf("post_id='%s'", $post->post_id))->setIntegrityCheck(false);
            $this->view->tags = $tags->fetchAll($query);
            
            $comments = new Comment();
            $query = $comments->select()->where(sprintf("post_id='%s'", $post->post_id));
            $this->view->comments = $comments->fetchAll($query);
        
        $form = new forms_Comment();
            
        if ($request->isPost()) {
            
            $values = $request->getPost();
            
            if ($form->isValid($values)) {
                
               $row = $comments->createRow($values);
                   
               $row->created_on = new Zend_Db_Expr('NOW()');
               $row->updated_on = new Zend_Db_Expr('NOW()');
               $row->ip = $request->getServer('REMOTE_ADDR');
               $row->post_id =  $post->post_id;
               
               $row->save();
               
               $form->setDescription('Comment has been posted.');
               
            } else {
                $form->populate($values);
                $form->setDescription('Form has errors.');
            }
           
        }
        }
        
        $this->view->form = $form;
    }
    function categoryAction ()
    {
        $request = $this->getRequest();
        $name = $request->getParam('name');
        $category = new Category();
        $query = $category->select()->from('category', array('num_comments' => new Zend_Db_Expr('COUNT(comment_id)') , 'month' => 'SUBSTRING(MONTHNAME(post.created_on),1,3)' , 'day' => 'DAY(post.created_on)'))->join('post_category', 'post_category.category_id=category.category_id', array())->join('post', 'post.post_id=post_category.post_id', array('*'))->join('users', 'users.user_id=post.user_id', 'username')->joinleft('comment', 'comment.post_id=post.post_id', array())->group('post.post_id')->where(sprintf("post.status = 1 AND category.url='%s'", $name))->order('post.created_on DESC')->setIntegrityCheck(false);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }
    function tagAction ()
    {
        $request = $this->getRequest();
        $name = $request->getParam('name');
        $tags = new Tag();
        $query = $tags->select()->from('tag', array('num_comments' => new Zend_Db_Expr('COUNT(comment_id)') , 'month' => 'SUBSTRING(MONTHNAME(post.created_on),1,3)' , 'day' => 'DAY(post.created_on)'))->join('post_tag', 'post_tag.tag_id=tag.tag_id', array())->join('post', 'post.post_id=post_tag.post_id', array('*'))->join('users', 'users.user_id=post.user_id', 'username')->joinleft('comment', 'comment.post_id=post.post_id', array())->group('post.post_id')->where(sprintf("post.status = 1 AND tag.url='%s'", $name))->order('post.created_on DESC')->setIntegrityCheck(false);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
    }
    function archiveAction ()
    {
        $request = $this->getRequest();
        $year = $request->getParam('year');
        $month = $request->getParam('month');
        $posts = new Post();
        $query = $posts->select()->from('post', array('*' , 'num_comments' => new Zend_Db_Expr('COUNT(comment_id)') , 'month' => 'SUBSTRING(MONTHNAME(post.created_on),1,3)' , 'day' => 'DAY(post.created_on)'))->join('users', 'users.user_id=post.user_id', 'username')->joinleft('comment', 'comment.post_id=post.post_id', array())->group('post.post_id')->where(sprintf("post.status = 1 and MONTH(post.created_on)='%s' and YEAR(post.created_on)='%s'", $month, $year))->order('post.created_on DESC')->setIntegrityCheck(false);
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbTableSelect($query));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $this->view->paginator = $paginator;
        $this->view->date = new Zend_Date(array('year' => $year , 'month' => $month , 'day' => 01));
    }
    
    function searchAction() {
        
    }
    
    function trackbackAction() {
        
    }
    
}
