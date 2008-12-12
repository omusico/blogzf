<?php
class Category extends Concentre_Db_Nested_Tree
{
    protected $_name = 'category';
    protected $_primary = 'category_id';
    protected $_left = 'category_left';
    protected $_right = 'category_right';
    protected $_label = 'category_title';
    protected $_url = 'category_url';
}