<?php
class Forms_Authentication extends Zend_Form
{
    public function __construct ($options = null, $user = null)
    {
        parent::__construct($options);
        $this->setName('authentication');
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Email')->addFilter('StringToLower')->setRequired(true)->addValidator('NotEmpty', true);
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Clave')->setRequired(true)->addValidator('NotEmpty', true);
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Entrar');
        $this->addElements(array($username , $password , $submit));
    }
}
