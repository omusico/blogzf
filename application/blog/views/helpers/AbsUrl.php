<?php
class Zend_View_Helper_AbsUrl
{
    protected $_view = null;
    public function setView ($view)
    {
        $this->_view = $view;
    }
    public function AbsUrl (array $urlOptions = array(), $name = null, $reset = false)
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $url = $this->_view->url($urlOptions, $name, $reset);
        list($scheme,$version) = explode('/',strtolower($request->getServer('SERVER_PROTOCOL')));
        
        return $scheme . ($request->getServer('HTTPS')?'s':'') . '://' . $request->getServer('HTTP_HOST') . $url;
    }
}
?>
