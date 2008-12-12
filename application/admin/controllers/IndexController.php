<?php
class Admin_IndexController extends Blogzf_Controller_Action
{
    public function indexAction ()
    {
        $form = new forms_Authentication();
        if ($this->_request->isPost()) {
            $credentials = $this->_request->getPost();
            if ($form->isValid($credentials)) {
                $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
                
               $authAdapter = new Zend_Auth_Adapter_DbTable(
                   Zend_Db_Table::getDefaultAdapter(),
                   'users','username','password', 'MD5(?) AND status="ENABLED"');

                // Set the input credential values to authenticate against
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