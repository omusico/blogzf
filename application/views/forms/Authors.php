<?php
class forms_Users extends Zend_Form
{
    public function __construct( $user = null, $options = null)
    {
        /**
         * Seteamos los valores default, en caso que no vengan
         */
        $usernameValue = ( $user == null ) ? '' : $user->username ;
        $firstNameValue = ( $user == null ) ? '' : $user->first_name;
        $lastNameValue = ( $user == null ) ? '' : $user->last_name;
        $creationDateValue = ( $user == null ) ? '' : $user->creation_date;
        $lockedValue = ( $user == null ) ? 'F' : $user->locked ;
        $customerIdValue = ( $user == null ) ? 0 : $user->customer_id ;
        $userIdValue = ( $user == null ) ? 0 : $user->user_id ;
        $endDateValue = ( $user == null ) ? '' : $user->end_date ;
        parent::__construct( $options );
        $this->setName( 'Authors' );

        $username = new Zend_Form_Element_Text( 'username' );
        $username->setLabel( 'Email' )
            ->addFilter( 'StringToLower' )
            ->setRequired( true )
            ->addValidator( 'NotEmpty', true )
            ->addValidator( 'EmailAddress' )
            ->setValue( $usernameValue );

        $password = new Zend_Form_Element_Password( 'password' );
        $password->setLabel( 'Clave' )
            ->setRequired( true )
            ->addValidator( 'NotEmpty' );

        $displayName = new Zend_Form_Element_Text( 'display_name' );
        $displayName->setLabel( 'Nombre que aparecera en la pagina' )
            ->setRequired( true )
            ->addValidator( 'NotEmpty' )
            ->setValue( $displayNameValue );
        
        $status = new Zend_Form_Element_Text( 'status' );
        $status->setLabel( 'Estado' )
            ->setValue( $statusValue )
            ->setAttrib( 'readOnly', true );

        $submit = new Zend_Form_Element_Submit( 'submit' );
        $submit->setLabel( 'Enviar' );
        $this->addElements(
            array($username, $password, $displayName, $status, $submit) );
    }
}