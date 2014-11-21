<?php


class Fonix_Filter_WordBreak implements Zend_Filter_Interface
{
	
    public function filter($value)
    {
		$words = explode(' ',$value);

		foreach ($words as $key => $word) {
			if (strlen($word) > 30) {
				$word_short = '';
				for ($i=0;$i<strlen($word);$i+=30) {
					$word_short .= substr($word,$i,30) . ' ';
				}
				
				$words[$key] = $word_short;
			} else {
				$words[$key] = $word;
			}
		}
		
		return implode(' ',$words);
    }
}
