<?php

class Sebold_Controller_Action_Helper_LayoutHead extends Zend_Controller_Action_Helper_Abstract 
{
	
	public function preDispatch()
	{
		$bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
		$view = $bootstrap->getResource('view');
		
		$headTitle = $this->getRequest()->getModuleName() . '-' .
	                 $this->getRequest()->getControllerName() . '-' . 
	                 $this->getRequest()->getActionName() . '-head-title';
		$view->headTitle($view->translate($headTitle));
		
		$title = $this->getRequest()->getModuleName() . '-' .
                 $this->getRequest()->getControllerName() . '-' . 
                 $this->getRequest()->getActionName() . '-title';
		$view->placeholder('title')->set($view->translate($title));
	}
	
}