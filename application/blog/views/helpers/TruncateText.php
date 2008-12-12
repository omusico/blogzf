<?php
class Zend_View_Helper_TruncateText
{
    protected $_view = null;
    public function setView ($view)
    {
        $this->_view = $view;
    }
    function TruncateText ($string, $limit, $break = ".", $pad = "...")
    {
        if (strlen($string) <= $limit)
            return $string;
        if (false !== ($breakpoint = strpos($string, $break, $limit))) {
            if ($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }
        return $string;
    }
}
?>
