<?php

class Zend_View_Helper_NumComments{
    
    protected $_view = null;
	
    public function setView(Zend_View_Interface $view) {
		$this->_view = $view;
    }
          
    function NumComments($num) {

      switch ($num) {
        case 0:
             return 'no comment';
             break;
        case 1: 
             return sprintf('<a href="%s">1 comment</a>', '#');
             break;
        default:
             return sprintf('<a href="%s">%s comments</a>','#', $num);
             break;
      }       
        
    }
   
}
