<?php


class Fonix_Filter_StaticLimiter 
{
	
    public static function filter($string, $limit, $final){
		
		
		if(strlen($string) > $limit){
			
			return substr($string, 0, $limit) . $final;
			
		} else {
			
			return $string;
			
		}
		
		
    }
}
