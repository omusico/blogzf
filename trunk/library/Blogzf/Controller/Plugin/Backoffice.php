<?php
/**
 * Plugin para administrar los layout de nuestro sistema. 
 *
 */
class Blogzf_Controller_Plugin_Backoffice extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup (Zend_Controller_Request_Abstract $request)
    {
    }
    public function routeShutdown (Zend_Controller_Request_Abstract $request)
    {
    }
    public function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    {
    }
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        
        $auth = Zend_Auth::getInstance();
        if ( $request->module == 'admin' ) {
            if( !$auth->hasIdentity() && $request->controller != 'index' ) {
                header('Location: /admin/');
            } elseif ( $auth->hasIdentity()  && $request->controller == 'index' ) {
                header('Location: /admin/dashboard/');
            }
        }
    }
    public function postDispatch ( Zend_Controller_Request_Abstract $request )
    {
    }
    public function dispatchLoopShutdown ()
    {
    }
}