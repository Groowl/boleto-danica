<?php

// library/Heerdt/Form/DisplayGroup.php
 
class Heerdt_Form_DisplayGroup extends Zend_Form_DisplayGroup
{
    /**
     * Load default decorators
     * 
     * @return void
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
 
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Description', array('tag'   => 'li',
                                                     'class' => 'group_description'))
                 ->addDecorator('FormElements')
                 ->addDecorator('HtmlTag', array('tag' => 'ol'))
                 ->addDecorator('Fieldset');
        }
    }
}