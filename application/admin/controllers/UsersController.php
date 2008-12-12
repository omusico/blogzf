<?php
class Admin_UsersController extends Blogzf_Controller_Action
{
    public function createAction ()
    {
        $form = new forms_Users();
        if ($this->_request->isPost()) {
            $userData = $this->_request->getPost();
            if ($form->isValid($userData)) {
                /**
                 * Si los datos estan ok, Creamos el registro
                 */
                unset($userData['submit']);
                $users = new Users();
                $userData['password'] = md5($userData['password']);
                $userId = $users->add($userData);
                $this->_redirect('/users/read/');
            } else {
                $form->populate($userData);
            }
        }
        $this->form = $form;
    }
    public function readAction ()
    {
        /**
         * Configuramos el paginador, para que me traiga todos los resultados paginados.
         */
        Zend_Paginator::setDefaultScrollingStyle('all');
        /**
         * Este es el tpl que voy a usar como paginador
         */
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        $query = $this->_db->select()->from('users');
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($query));
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $this->view->paginator = $paginator;
    }
    public function updateAction ()
    {}
    public function deleteAction ()
    {}
}