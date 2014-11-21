<?php
// library/Lepard/Form.php
 
class App_Form extends Zend_Form
{

    public $elementDecorators = array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
            array('Label', array('tag' => 'span', 'class' => 'control-label')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
        );
    public $checkDecorators = array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
            array('Label', array('tag' => 'span', 'class' => 'control-label')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group control-checkbox'))
        );

    public $buttonDecorators = array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group'))
        );
    /**
     * Constructor
     *
     * Add custom prefix path before parent constructor
     *
     * @param mixed $options
     * @return void
     */
    public function __construct($options = null)
    {

        $this->setDecorators(array(
            array('Description', array('tag' => 'p', 'class' => 'description')),
            'formElements',
            array('form', array('class' => 'form-horizontal')),
        ));

        parent::__construct($options);
    }
 
}