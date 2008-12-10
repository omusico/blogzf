<?php
class Tag extends Zend_Db_Table_Abstract 
{
	protected $_name = 'tag';
	protected $_primary = 'tag_id';
	
	/*
    protected $_referenceMap    = array(
        'post_tag' => array(
            'columns'           => array('tag_id','post_id'),
            'refTableClass'     => 'post_tag',
            'refColumns'        => 'tag_id'
         )
    );
    */
	   	
}