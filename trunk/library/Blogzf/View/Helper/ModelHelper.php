<?php
class Blogzf_View_Helper_ModelHelper
{
    public function getModel( $modelName )
    {
        return new $modelName();   
    }
}