<?php

class IndexController extends Zend_Controller_Action

{	public function indexAction()
	{


		$field = "IF(boleto_pago.id is not null,'pago',IF(DATEDIFF(data_vencimento,CURDATE()) > 5,'pendente',IF(DATEDIFF(data_vencimento,CURDATE()) < -4,'vencido','vencendo')))";

		$where = ['1=1'];
		if ($this->getRequest()->getParam('status') != '') {
			$where[] = $field." = '".$this->getRequest()->getParam('status')."'";
		}
		if ($this->getRequest()->getParam('bsufca') != '') {
			$busca = trim($this->getRequest()->getParam('busca'));
			$where[] = "(boleto.nosso_numero = '$busca' OR boleto.numero_documento = '$busca' OR boleto.nome like '%$busca%' or boleto.linha_digitavel = '$busca' or REPLACE(REPLACE(boleto.linha_digitavel,' ',''),'.','') = '$busca')";
		}

		$usuario = Zend_Auth::getInstance()->getIdentity();

		if ($usuario->tipo != 'admin') {
			$where[] = "email = '".$usuario->email."'";	
		}

		$query = "SELECT 
						boleto.*, 
						$field as status
					from 
						boleto
						left join boleto_pago on boleto.nosso_numero = boleto_pago.nosso_numero
					WHERE
						".implode(' AND ', $where)."
					order by
						FIELD(status,'vencedo', 'pendente', 'vencido', 'pago') ASC, data_vencimento ASC ";

		$this->view->rows = App_Db_Adapter::get()->fetchAll($query);
	}
}
