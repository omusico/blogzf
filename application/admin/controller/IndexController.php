<?php
class Admin_IndexController extends Zend_Controller_Action
{
    private $_flashMessenger;
    public function indexAction ()
    {
        $this->view->addHelperPath('Blogzf/View/Helper', 'Blogzf_View_Helper_');
        Zend_Controller_Action_HelperBroker::addHelper(new Blogzf_Controller_Action_Helper_BlogzfFlashMessenger());
        $this->_flashMessenger = $this->_helper->getHelper('BlogzfFlashMessenger');
        $form = new forms_Authentication();
        if ($this->_request->isPost()) {
            $credentials = $this->_request->getPost();
            if ($form->isValid($credentials)) {
                $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
                $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                $authAdapter->setTableName('users');
                $authAdapter->setIdentityColumn('username');
                $authAdapter->setCredentialColumn('password');
                $authAdapter->setIdentity($credentials['username']);
                $authAdapter->setCredential($credentials['password']);
                $result = Zend_Auth::getInstance()->authenticate($authAdapter);
                if ($result->isValid()) {
                    $this->_redirect('/admin/dashboard/');
                }
                $this->_flashMessenger->addError('usuario incorrecto');
                $form->populate($credentials);
            } else {
                $this->_flashMessenger->addError('Hay datos invalidos o vacios');
                $form->populate($credentials);
            }
        }
        $this->view->form = $form;
    }
    public function logoutAction ()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/admin');
    }
}