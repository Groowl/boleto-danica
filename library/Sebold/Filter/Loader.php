<?php
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class Fonix_Filter_Loader  extends Zend_Controller_Action_Helper_Abstract {
	
	public $FonixFilters;
	
	public function init(){
		
		$control = $this->getActionController();
		
		$this->FonixFilters = new Zend_Filter();
		$this->FonixFilters->addFilter( new Fonix_Filter_PlainTextToHtml());
		
		$control->FonixFilters = $this->FonixFilters;
		
	}
	
	public function preDispatch(){
		
		// passa o FonixFilters para os view/scripts/
    	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
  		$viewRenderer->view->FonixFilters = $this->FonixFilters;
  		
  		// passa o FonixFilters para os scripts de layout (ex: na pasta layout, main.phtml ou header.phtml)
  		Zend_Layout::getMvcInstance()->getView()->FonixFilters = $this->FonixFilters;
		
	}
	
}