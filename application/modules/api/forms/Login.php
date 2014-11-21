<?php

class Api_Form_Login extends Zend_Form 
{
	
	public function init()
	{
		$this->setDecorators(array(
			array('Description', array('tag' => 'p', 'class' => 'error-message')),
			'formElements',
			'fieldset',
			array('form', array('class' => 'forms-signin')),
		));
		$this->setAction($this->getView()->url(array('index')) . '/qg/index/login');
		//$this->setLegend('Login');
		$this->setMethod(Zend_Form::METHOD_POST);
		
		$this->addElements(array(
			'email' => array(
				'text',array(
					'label' => 'Digite seu e-mail',
					'class' => 'form-control',
					'validators' => array(
						'EmailAddress'
					),
					'required' => true
				)
			),
			'senha' => array(
				'password',array(
					'class' => 'form-control',
					'label' => 'Senha',
					'required' => true
				)
			),/*
			'bol_lembrar' => array(
				'multiCheckbox',array(
					'multiOptions' => array(
						'1' => 'Lembre-me'
					)
				)
			),*/
			'btn_enviar' => array(
				'submit',array(
					'class' => 'btn btn-lg btn-primary btn-block',
					'label' => 'Acessar',
					'ignore' => true
				)
			)
		));
		
		//$this->getElement('bol_lembrar')->removeDecorator('label');
		//$this->getElement('bol_lembrar')->getDecorator('HtmlTag')->setOption('class', 'lembrar');
	}
	
}