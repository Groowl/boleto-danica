<?php 


class Sebold_Controller_Plugin_CompressResponse extends Zend_Controller_Plugin_Abstract
{

	public function dispatchLoopShutdown()
	{
		if (APPLICATION_ENV == 'development') {
			return;
		}
		$content = Zend_Controller_Front::getInstance()->getResponse()->getBody();

		$content = preg_replace(
				array(
						'/(\x20{2,})/',   // extra-white spaces
						'/\t/',           // tab
						'/\n\r/',          // blank lines
						'/\n+/',          // blank lines
						'/\r+/'          // blank lines
				),
				array(' ', '', '',"\n",''),
				$content
		);
		Zend_Controller_Front::getInstance()->getResponse()->setBody($content);
	}

}