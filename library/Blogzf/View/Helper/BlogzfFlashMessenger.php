<?php
class Blogzf_View_Helper_BlogzfFlashMessenger extends Zend_View_Helper_FormElement
{
    private $_types = array( Blogzf_Controller_Action_Helper_BlogzfFlashMessenger::ERROR , 
        Blogzf_Controller_Action_Helper_BlogzfFlashMessenger::WARNING , 
        Blogzf_Controller_Action_Helper_BlogzfFlashMessenger::NOTICE , 
        Blogzf_Controller_Action_Helper_BlogzfFlashMessenger::SUCCESS);
    public function blogZfFlashMessenger ()
    {
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('BlogzfFlashMessenger');
        $html = '';
        foreach ($this->_types as $type) {
            $messages = $flashMessenger->getMessages($type);
            if (! $messages) {
                $messages = $flashMessenger->getCurrentMessages($type);
            }
            if ($messages) {
                if (! $html) {
                    $html .= '<ul class="messages">';
                }
                $html .= '<li class="' . $type . '-msg">';
                $html .= '<ul>';
                foreach ($messages as $message) {
                    $html .= '<li>';
                    $html .= $message->message;
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</li>';
            }
        }
        if ( !empty($html)) {
            $html .= '</ul>';
        }
        return $html;
    }
}