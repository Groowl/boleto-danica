<?php

require_once 'Zend/Form/Element/Xhtml.php';

class Sebold_Qg_Form_Element_UploadFile extends Zend_Form_Element_Xhtml
{
    /**
     * Use formHidden view helper by default
     * @var string
     */
    public $helper = 'uploadFile';
}
