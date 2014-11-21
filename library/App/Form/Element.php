<?php
// library/Tolerable/Form/Element.php
 
class Heerdt_Form_Element extends Zend_Form_Element
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
            $this->addDecorator('ViewHelper')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array('tag' => 'p',
                                                     'class' => 'description'))
                 ->addDecorator('Label', array('requiredSuffix' => ' *'))
                 ->addDecorator('HtmlTag', array('tag' => 'li',
                                                 'id'  => $this->getName() . '-element'));
        }
    }
}