<?php

class UsuariosController extends Zend_Controller_Action {	


	public function indexAction() {

		$usuario = Zend_Auth::getInstance()->getIdentity();

		$this->view->tipo = $usuario->tipo;

		$this->view->rows = App_Db_Adapter::get()->fetchAll("Select * from usuario order by nome");
	}
	public function alterarSenhaAction() {

		$usuario = Zend_Auth::getInstance()->getIdentity();

		if ($usuario->tipo != 'admin' && $usuario->email != $this->getRequest()->getParam('email')) {
			$this->getHelper('Redirector')
			 	->setGotoRoute(array('module' => 'api',
			                      'controller' => 'index',
			 					  'action' => 'index',
			 					  'message-type' => 'error',
			 					  'message' => 'Operação não autorizada.'),
			 				null,
			 				true);
		}

		$update = array('nome' => $this->getRequest()->getParam('nome'), 'cidade' => $this->getRequest()->getParam('cidade'), 'estado' => $this->getRequest()->getParam('estado'));
		if ($this->getRequest()->getParam('senha') != '' && $this->getRequest()->getParam('nova-senha') != '' && $this->getRequest()->getParam('senha') == $this->getRequest()->getParam('nova-senha')) {
			$update['senha'] = md5($this->getRequest()->getParam('senha'));
		}


		App_Db_Adapter::get()->update("usuario",$update,"email = '".$this->getRequest()->getParam('email')."'");

		if ($usuario->tipo == 'admin') {
			$this->getHelper('Redirector')
			 	->setGotoRoute(array('module' => 'api',
			                      'controller' => 'usuarios',
			 					  'action' => 'index',
			 					  'message-type' => 'success',
			 					  'message' => 'Dados Alterados'),
			 				null,
			 				true);
		} else {
			$this->getHelper('Redirector')
			 	->setGotoRoute(array('module' => 'api',
			                      'controller' => 'index',
			 					  'action' => 'index',
			 					  'message-type' => 'success',
			 					  'message' => 'Dados de Usuário Alterados'),
			 				null,
			 				true);
		}
	}
}
