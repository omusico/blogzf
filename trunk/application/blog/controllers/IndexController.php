<?php
class Blog_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
         $this->_helper->redirector('index','post');  
    }

}
