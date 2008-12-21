<?php
class Admin_SidebarController extends Blogzf_Controller_Action
{
    public function preDispatch ()
    {}
    public function menutopAction ()
    {
        /*if (! Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->viewRenderer->setNoRender();
        }*/
    }
    public function rightcontentAction ()
    {
       /** if (! Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->viewRenderer->setNoRender();
        }*/
    }
    public function footerAction ()
    {}
    public function headerAction()
    {
        /*if (! Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->viewRenderer->setNoRender();
        } */       
        
    }
}