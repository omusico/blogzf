<?php

class Zend_View_Helper_NestedTree {
    
    protected $_view = null;
	
    public function setView(Zend_View_Interface $view) {
		$this->_view = $view;
    }
          
    function NestedTree($tree) {

      $li_class = '';
      $last_level = 0;
      $str = '';
      
      foreach($tree as $node)
      {
          if($node->depth == $last_level && $last_level != 0)  {
              $str.= '</li>';
          } else if($node->depth < $last_level) {
              $str.= str_repeat('</li></ul>',$last_level - $node->depth).'</li>';
              $last_level = $node->depth;
          } else if($node->depth > $last_level ) {
              $last_level = $node->depth;
              $str.= '<ul>';
          }
    
          $str.= '<li><a href="'.$this->_view->url(array('name' => $node->url),'category').'">' . $node->label. '</a>';
          
      }

      if($last_level > 0) {
          $str.= str_repeat("</li>\n</ul>\n",$last_level)."</li>\n";
      } else {
         $str.= '</li>';
      }
      
      return $str; 
        
    }

     
    
    
}
