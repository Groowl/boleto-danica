<?php


class Fonix_Filter_PlainTextToBbCode implements Zend_Filter_Interface
{
	
    public function filter($value)
    {
    	$patterns = array(
			/*'h2' => array(
				'pattern' => '#\[quote(?:=&quot;(.*?)&quot;)?:$uid\]((?!\[quote(?:=&quot;.*?&quot;)?:$uid\]).)?#ise',
				'replace' => '<h2>${1}</h2><div class="quote">${2}<div>'
			),*/
			'quoteOpen' => array(
				'pattern' => '/\[quote:(.*?)="(.*?)"]/',
				'replace' => '<div class="quote"><h2>${1}: </h2>'
			),
			'quoteClose' => array(
				'pattern' => '/\[\/quote]/',
				'replace' => '</div>'
			)
		);
		
		foreach ($patterns as $key => $pattern) {
			$value = preg_replace($pattern['pattern'], $pattern['replace'], $value);
		}
		
		return $value;
    }
}

