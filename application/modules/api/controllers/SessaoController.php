<?php

class SessaoController extends Zend_Controller_Action
{

	public function loginAction()
	{
		$this->getHelper('layout')->setLayout('login');
				
		if (Zend_Auth::getInstance()->hasIdentity()) {
			return $this->getHelper('Redirector')
				 ->setGotoUrl('api/index');
		}

		$formulario = new Api_Form_Login();
		$formulario->setAction($this->view->url());
		
		if ($this->getRequest()->isPost()) {
			if ($formulario->isValid($this->getRequest()->getParams())) {
				$auth = Zend_Auth::getInstance();
				
				if ($auth->hasIdentity()) {
					$auth->clearIdentity();
				}
				
				$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
				$authAdapter->setTableName('usuario');
				$authAdapter->setIdentityColumn('email');
				$authAdapter->setCredentialColumn('senha');
				
				$authAdapter->setIdentity($formulario->getValue('email'));
				$authAdapter->setCredential(md5($formulario->getValue('senha')));
				
					// $usuario->tipo = 'qgusuario';
				$result = $auth->authenticate($authAdapter);

				if ($result->isValid()) {
					$usuario = $authAdapter->getResultRowObject(null,'senha');
					$usuario->tipo = $usuario->tipo;
					$auth->getStorage()->write($usuario);

					$this->getHelper('Redirector')
						 ->setGotoUrl('');
				} else {
					$formulario->setDescription('E-mail ou senha digitados não são válidos. Verifique e tente novamente.');
				}
			} else {
				$formulario->setDescription('E-mail ou senha digitados não são válidos. Verifique e tente novamente.');
			}
		}
		
		$this->view->formulario = $formulario;
	}
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		
		$this->getHelper('Redirector')
			 ->setGotoRoute(array('module' => 'api',
			                      'controller' => 'sessao',
			 					  'action' => 'login'),
			 				null,
			 				true);
	}
}
