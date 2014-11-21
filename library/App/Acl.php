<?php

class App_Acl extends Zend_Acl 
{
	
	public function __construct(Zend_Auth $auth)
	{
				 
		$this->addRole(new Zend_Acl_Role('guest'));
		$this->addRole(new Zend_Acl_Role('member'),array('guest'));
		$this->addRole(new Zend_Acl_Role('admin'),array('guest','member'));
		 
		 
		$this->add(new Zend_Acl_Resource('api-index'));
		$this->add(new Zend_Acl_Resource('api-index-index'));
		$this->add(new Zend_Acl_Resource('api-sessao'));
		$this->add(new Zend_Acl_Resource('api-sessao-index'));
		$this->add(new Zend_Acl_Resource('api-sessao-login'));
		$this->add(new Zend_Acl_Resource('api-sessao-logout'));
		$this->add(new Zend_Acl_Resource('api-boletos'));
		$this->add(new Zend_Acl_Resource('api-boletos-index'));
		$this->add(new Zend_Acl_Resource('api-error'));
		$this->add(new Zend_Acl_Resource('api-error-error'));
		 
		$this->deny('guest');

		$this->allow('guest', 'api-sessao');
		$this->allow('guest', 'api-error');

		$this->allow('admin','api-sessao');
		$this->allow('member','api-index');
		$this->allow('member','api-boletos');
		$this->allow('admin','api-index');
		$this->allow('admin','api-boletos');
		
		// Qg
	}
	
}