<?php

class Zend_View_Helper_NestedTree {
    
    protected $_view = null;
	
    public function setView(Zend_View_Interface $view) {
		$this->_view = $view;
    }
          
    function NestedTree($tree) {

        $depth = 0;
        $num_at_depth = 0;
        $str = "<ul>\n<li>";
     
        foreach ($tree as $leaf) {
            $diffdepth=0;
              if ($leaf->depth > $depth) {
                $str.= "\n<ul>\n<li>";
                $depth = $leaf->depth;
                    $num_at_depth = 0;
              }
              if ($leaf->depth < $depth) {
                $diffdepth= $depth - $leaf->depth;
                while ($diffdepth > 0){
                    $str.="</li>\n</ul>\n";
                    $diffdepth -- ;
                }
                    $depth = $term->depth;
              }
              if (($leaf->depth == $depth) && ($num_at_depth > 0)) {
                  $str.="</li>\n<li>";
                }
                $str.= $leaf->label;  
                $num_at_depth ++;
            }
		        
         $str.= "</li>\n</ul>\n";

         return $str; 
        
    }
   
}
