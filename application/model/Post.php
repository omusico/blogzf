<?php
class Post extends Zend_Db_Table_Abstract
{
    protected $_name = 'post';
    protected $_primary = 'post_id';
    protected $_referenceMap = array( 
        'Comment' => array( 
        	'columns' => array( 'post_id' ), 
            'refTableClass' => 'Post', 
            'refColumns' => array('post_id' )),
        'PostCategory' => array( 
            'columns' => array( 'post_id' ), 
            'refTableClass' => 'PostCategory', 
            'refColumns' => array( 'post_id' )),
        'PostTags' => array( 
            'columns' => array( 'post_id' ), 
            'refTableClass' => 'PostTag', 
            'refColumns' => array( 'post_id' )),
        'PostMedia' => array( 
            'columns' => array( 'post_id' ), 
            'refTableClass' => 'PostMedia', 
            'refColumns' => array( 'post_id' )));      
}
