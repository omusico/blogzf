<?php
class PageController extends Zend_Controller_Action
{
    public function preDispatch()
    {
    }
        
    public function indexAction()
    {
        
        $id = $this->getRequest()->getParam('id');
           
        $page = new Page();
        $query = $page->select()
                       ->where(sprintf("`page_id`='%s'", $id ));

        $this->view->page = $page->fetchRow($query);
    }
    

}
