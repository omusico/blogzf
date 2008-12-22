<?php
/**
 * Capa de abstraccion de Zend_Controller_Action
 *
 */
class Blogzf_Controller_Action extends Zend_Controller_Action
{
    protected $_flashMessenger;
    protected $_user;
    public function init()
    {
        parent::init();
        $this->view->addHelperPath('Blogzf/View/Helper', 'Blogzf_View_Helper_' );
        Zend_Controller_Action_HelperBroker::addHelper(
            new Blogzf_Controller_Action_Helper_BlogzfFlashMessenger());
        $this->_flashMessenger = $this->_helper->getHelper('BlogzfFlashMessenger'); 
        /**
         * Configuramos el paginador, para que me traiga todos los resultados paginados.
         */
        Zend_Paginator::setDefaultScrollingStyle('all');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('/paginator/all.phtml');
        /**
         * Por ahora hardocdeamos esto, despues esto va a ser un objeto user, con todos los datos del usuario logueado
         */
        $this->_user = new stdClass();
        $this->_user->id = 1;//Zend_Auth::getInstance()->getIdentity();
    }
}
