<?php
class IndexController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        $this->_helper->redirector->gotoRoute(array(),'blog-home');
    }
}
