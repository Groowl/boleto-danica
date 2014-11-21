<?php

class Sebold_Mail extends Zend_Mail 
{
	
	protected $_tempBodyText = '';
	protected $_tempBodyHtml = '';
	
	public function setBodyText($txt, $charset = null, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE)
	{
		$this->_tempBodyText = $txt;
		parent::setBodyText($txt, $charset, $encoding);
		return $this;
	}
	
	public function setBodyHtml($html, $charset = null, $encoding = Zend_Mime::ENCODING_QUOTEDPRINTABLE)
	{
		$this->_tempBodyHtml = $html;
		return parent::setBodyHtml($html, $charset, $encoding);
	}
	
	public function send($transport = null)
	{
		$data = array(
			'txt_headers'  => serialize($this->getHeaders()),
			'txt_bodytext' => $this->_tempBodyText,
			'txt_bodyhtml' => $this->_tempBodyHtml,
			'dtt_hora'     => date('Y-m-d H:i:s')
		);
		Sebold_Db_Adapter::get()->insert('zendmailbuffer',$data);
		
		return parent::send($transport);
	}
	
}