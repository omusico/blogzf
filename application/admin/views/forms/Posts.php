<?php
class forms_Posts extends Zend_Form
{
    public function __construct( $options = null, $user = null )
    {
        parent::__construct( $options );
        $this->setName( 'Posts' );

        $title = new Zend_Form_Element_Text( 'title' );
        $title->setLabel( 'Titulo' )
            ->setRequired( true )
            ->addValidator( 'NotEmpty', true );

        $content = new Zend_Form_Element_Textarea( 'content' );
        $content->setLabel( 'Contenido' )
            ->setRequired( true )
            ->addValidator( 'NotEmpty', true );            

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Enviar' );
        $this->addElements( array( $title, $content, $submit) );
    }
}