<?php
class Blog_SidebarController extends Zend_Controller_Action
{
    public function preDispatch ()
    {
        $this->view->title = 'Blog ZF';
    }
    public function menutopAction ()
    {}
    public function rightcontentAction ()
    {
        $categories = new Category();
        $this->view->categories = $categories->enumSubTree(1, true);
        $posts = new Post();
        $query = $posts->select()->where('post_status = 1')->limit(10)->order('post_created_on DESC');
        $this->view->recentPosts = $posts->fetchAll($query);
        $query = $posts->select()->where('post_selected = 1')->limit(10)->order('post_created_on DESC');
        $this->view->selectedPosts = $posts->fetchAll($query);
        $query = $posts->select()->from('post', array('month' => new Zend_Db_Expr('MONTH(post_created_on)') , 'monthname' => new Zend_Db_Expr('MONTHNAME(post_created_on)') , 'year' => new Zend_Db_Expr('YEAR(post_created_on)') , 'total' => new Zend_Db_Expr('COUNT(post_id)')))->group(array('year' , 'month'))->order('post_created_on DESC');
        $this->view->archivesByMonth = $posts->fetchAll($query);
        $tags = new Tag();
        $query = $tags->select()->from('tag', array('tag_word' , 'total' => new Zend_Db_Expr('COUNT(tag.tag_id)')))->join('post_tag', 'post_tag.tag_id=tag.tag_id', array())->group('post_tag.tag_id')->setIntegrityCheck(false);
        $this->view->cloudTags = $tags->getAdapter()->fetchPairs($query);
        /*
        $flickr = new Zend_Service_Flickr('b1db2f69a586256303a1fecee26bb211');
        $this->view->flickr = $flickr->userSearch('songosalsa@yahoo.es');
   */
    }
    public function footerAction ()
    {}
}


