<?php

class Sebold_Controller_Action_Helper_LayoutLoader extends Zend_Controller_Action_Helper_Abstract 
{
	
	public function preDispatch() 
	{
		$bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		$config = $bootstrap->getOptions();
		$module = $this->getRequest()->getModuleName();
		if (isset($config[$module]['resources']['layout']['layout'])) {
			$layoutScript = $config[$module]['resources']['layout']['layout'];
			
			$this->getActionController()
				 ->getHelper('layout')
				 ->setLayoutPath(APPLICATION_PATH . '/modules/' . $module . '/layouts/scripts/')
				 ->setLayout($layoutScript);
		}
		$this->getResponse()->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
	}
	
}