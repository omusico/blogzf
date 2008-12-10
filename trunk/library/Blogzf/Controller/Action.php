<?php
/**
 * Capa de abstraccion de Zend_Controller_Action
 *
 */
class Blogzf_Controller_Action extends Zend_Controller_Action
{
    protected $_flashMessenger;
    public function init()
    {
        parent::init();
        $this->view->addHelperPath('Blogzf/View/Helper', 'Blogzf_View_Helper_' );
        Zend_Controller_Action_HelperBroker::addHelper(
            new Blogzf_Controller_Action_Helper_BlogzfFlashMessenger());
        $this->_flashMessenger = $this->_helper->getHelper('BlogzfFlashMessenger'); 
    }
    
}
