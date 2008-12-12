<?php
class forms_Posts extends Zend_Form
{
    public function __construct ($options = null, $user = null)
    {
        parent::__construct($options);
        $this->setName('Posts');
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Titulo')->setRequired(true)->addValidator('NotEmpty', true);
        $content = new Zend_Form_Element_Textarea('content');
        $content->setLabel('Contenido')->setRequired(true)->addValidator('NotEmpty', true);
        $comment = new Zend_Form_Element_Checkbox('comment');
        $comment->setLabel('Comentarios');
        $lov = new Lov();
        $unformatStates = $lov->findForType('status');
        foreach ($unformatStates as $st) {
            $states[$st->value] = $st->value;
        }
        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Estado')->setMultiOptions($states)->setRequired(true)->addValidator('NotEmpty', true);
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Enviar');
        $this->addElements(array($title , $content , $comment , $status , $submit));
    }
}