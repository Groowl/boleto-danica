<?php


class Fonix_Filter_PlainTextToHtml implements Zend_Filter_Interface
{
	
    public function filter($value)
    {
    	$patterns = array(
			'h2' => array(
				'pattern' => "/== (.+) ==/i",
				'replace' => '<h2>${1}</h2>'
			),
			'li' => array(
				'pattern' => "/--(.+)(\r|\n)/im",
				'replace' => "<li>$1</li>"
			),
			'img' => array(
				'pattern' => "/\[\[Imagem:(.+)\](.+)?\]/im",
				'replace' => '<div class="$2"><img src="http://pt.muky.com.br/image/$1.jpg" /></div>'
			),
			'b' => array(
				'pattern' => "/'''(.+)'''/i",
				'replace' => '<b>${1}</b>'
			),
			'i' => array(
				'pattern' => "/''(.+)''/i",
				'replace' => '<i>${1}</i>'
			),
			'b' => array(
				'pattern' => "/'''(.+)'''/i",
				'replace' => '<b>${1}</b>'
			),
			'a' => array(
				'pattern' => "/\[(.+)\[(.+)\]\]/i",
				'replace' => '<a href="${1}">${2}</a>'
			),
			'p' => array(
				'pattern' => "/(.+)/im",
				'replace' => "<p>$1</p>"
			),
		);
		
		foreach ($patterns as $key => $pattern) {
			$value = preg_replace($pattern['pattern'], $pattern['replace'], $value);
		}
		
		return $value;
    }
}
