<?php
class Lov extends Zend_Db_Table_Abstract 
{
	protected $_name = 'lov';
	protected $_primary = 'lov_id';
	public function findForType( $type = null )
	{
	    try{
	        return $this->fetchAll(
	            $this->select()->where( 'type = ?', $type ));    
	    } catch ( Zend_Exception $e ) {
            echo "Caught exception: " . get_class($e) . "\n";
            echo "Message: " . $e->getMessage() . "\n";
	    }
	    
	}
}