<?php
class Admin_IndexController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        $form = new forms_Authentication();
        if ($this->_request->isPost()) {
            $credentials = $this->_request->getPost();
            if ($form->isValid( $credentials )) {
                $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
                $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
                $authAdapter->setTableName( 'users' );
                $authAdapter->setIdentityColumn('username');
                $authAdapter->setCredentialColumn('password');
                $authAdapter->setIdentity( $credentials['username'] );
                $authAdapter->setCredential( $credentials['password'] );
                $result = Zend_Auth::getInstance()->authenticate($authAdapter);
                if( $result->isValid() ){
                    $this->_redirect('/admin/dashboard/');    
                }
                $form->populate( $credentials );
            } else {
                $form->populate( $credentials );
            }
        }
        $this->view->form = $form;
    }
}