<?php

class App_Auth extends Zend_Controller_Plugin_Abstract  
{
	
    /**
     * Auth
     *
     * @return Zend_Auth
     */
	protected $_auth;
    /**
     * Acl
     *
     * @return Zend_Acl
     */
	protected $_acl;
	
	public function __construct()
	{
		$this->_auth = Zend_Auth::getInstance();
		$this->_acl  = new App_Acl($this->_auth);
		Zend_Registry::set('App_Acl',$this->_acl);
		
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_acl);
		
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{

		if ($this->_auth->hasIdentity()) {
			$usuario = Zend_Auth::getInstance()->getStorage()->read();
			$role = $usuario->tipo;
		} else {
			$role = 'guest';
		}
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');

		if ($request->getModuleName() == 'api') {
			$resource = 'api-' . $request->getControllerName();
			$resource2 = 'api-' . $request->getControllerName() . '-' . $request->getActionName();
			if (!$this->_acl->isAllowed($role, $resource) && !$this->_acl->isAllowed($role, $resource2)) {
					return $redirector->gotoUrl('sessao/login');
			}
		} else {
			//$this->getResponse()->setRawHeader('HTTP/1.1 403 Forbidden');
			$resource = 'default';
		}
		
		
	}
	
}