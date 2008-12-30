<?php
class Admin_SidebarController extends Blogzf_Controller_Action
{
    public function preDispatch ()
    {}
    public function menutopAction ()
    {$this->_helper->viewRenderer->setNoRender();
        /*if (! Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->viewRenderer->setNoRender();
        }*/
    }
    public function rightcontentAction ()
    {
        $this->_helper->viewRenderer->setNoRender();
       /** if (! Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->viewRenderer->setNoRender();
        }*/
    }
    public function footerAction ()
    {$this->_helper->viewRenderer->setNoRender();}
    public function headerAction()
    {$this->_helper->viewRenderer->setNoRender();
        /*if (! Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->viewRenderer->setNoRender();
        } */       
        
    }
}