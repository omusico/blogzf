<?php
class Blog_PageController extends Zend_Controller_Action
{
    public function preDispatch ()
    {}
    public function indexAction ()
    {
        $name = $this->getRequest()->getParam('name');
        $page = new Page();
        $query = $page->select()->where(sprintf("`url`='%s'", $name));
        $this->view->page = $page->fetchRow($query);
    }
}
