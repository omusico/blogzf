<?php
class forms_Comment extends Zend_Form
{
    
      protected static $_formDecorators = array(
                'FormElements',
                array('Description', 
                    array('tag' => 'p', 
                    	  'class' => 'none' ,
                    	  'escape' => 'false', 
                    	  'placement' => 'prepend')
                    ),
                'Form'
      );
             
      protected static $_buttonsDecorators = array(
                'ViewHelper',
                'Errors'
      );
    
      protected static $_elementDecorators = array(
          'ViewHelper',
          array(
                'Description',
                        array(
                        'tag'=>'span',
                        'class'=>'hint',
                        'escape' => false,
                        'placement' => 'append'
                        )

                ),
                'Errors',
                 array('Label',
                        array(
                        'placement' => 'append'
                        )
                ),
                
                 array(
                        'HtmlTag',
                        array(
                        'tag'=>'p',
                        'class'=>'field'
                        )
                )
        );
    
    
    public function init ()
    {
         $this->setName('postCommentForm')
              ->setAction($_SERVER['REQUEST_URI'])
              ->setMethod('POST');
               

         $elt = new Zend_Form_Element_Text('comment_author');
         $elt->setLabel('name')
             ->setRequired('true')
             ->setAttrib('class','text')
             ->addValidator('NotEmpty');

         $this->addElement($elt);

         $elt = new Zend_Form_Element_Text('comment_email');
         $elt->setLabel('mail')
             ->setDescription('will not be published')
             ->setRequired('true')
             ->setAttrib('class','text')
             ->addValidator('EmailAddress')
             ->addValidator('NotEmpty');

         $this->addElement($elt);
         
         $elt = new Zend_Form_Element_Text('comment_site');
         $elt->setLabel('website')
             ->setAttrib('class','text');


         $this->addElement($elt);
                  

         $elt = new Zend_Form_Element_Textarea('comment_content');
         $elt->setAttrib('rows','10')
             ->setAttrib('class','text')
             ->setAttrib('style','width: 100%');
          
         $this->addElement($elt);

         $elt = new Zend_Form_Element_Submit('submit');
         $elt->setAttrib('class','btn submit btn-orange')
             ->setIgnore(true);

         $this->addElement($elt);
         
         $this->clearDecorators()
             ->setDecorators(self::$_formDecorators)
             ->setElementDecorators(self::$_elementDecorators);
              
              
         $this->submit->setDecorators(self::$_buttonsDecorators);
       
        
    }
}