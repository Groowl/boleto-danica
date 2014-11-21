<?php

class Sync_IndexController extends Zend_Controller_Action
{
	public function indexAction()
	{

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);


		$dir = $this->view->baseUrl('/var/www/danica/public_html/arquivos/');

		$files = scandir($dir);
		echo '<pre>';

		$inserts = array();
		foreach ($files as $file) {
			if (in_array($file, array('.','..','importados'))) continue;

			$csv = file($dir.$file);

			$columns = explode(';',trim($csv[0]));
			foreach ($csv as $k => $row) {
				if ($k==0 || $row == '') continue;

				try {
					$values = array_combine($columns, array_slice(explode(';',trim($row)),0,count($columns)));	
				} catch (Exception $e) {
					print_r(explode(';', $row));
					die;
				}
				
				$d = explode('/',$values['data_documento']);
				$values['data_documento'] = $d[2].'-'.$d[1].'-'.$d[0];
				$d = explode('/',$values['data_vencimento']);
				$values['data_vencimento'] = $d[2].'-'.$d[1].'-'.$d[0];

				App_Db_Adapter::get()->insert('boleto',$values);
			}
			
			rename($dir.$file,$dir.'importados/'.$file);
		}
	}
}
