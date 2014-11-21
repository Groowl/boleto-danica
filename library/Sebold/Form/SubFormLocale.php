<?php

class Sebold_Form_SubFormLocale extends Zend_Form_SubForm 
{
	
	public function init() {
		$this->setDecorators(array(
			array('Description', array('tag' => 'p', 'class' => 'description')),
			'formElements',
			array('HtmlTag', array('tag' => 'dl')),
			array('fieldset', array('class' => 'localesubform')),
		));
	}
	
}