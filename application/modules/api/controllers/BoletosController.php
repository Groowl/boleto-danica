<?php

class BoletosController extends Zend_Controller_Action
{
	public function indexAction()
	{

		$this->_helper->layout->disableLayout();
		$usuario = Zend_Auth::getInstance()->getIdentity();

		$query = "SELECT 
						boleto.*
					from 
						boleto
						left join boleto_pago on boleto.nosso_numero = boleto_pago.nosso_numero
					WHERE
						boleto.nosso_numero = '".$this->getRequest()->getParam('nosso_numero')."'
						AND boleto_pago.id is null
						".($usuario->tipo == 'admin' ? "" : "AND email = '".$usuario->email."'")."";

		$this->view->boleto = App_Db_Adapter::get()->fetchRow($query);

		
		$this->render('bradesco');	

	}
}
