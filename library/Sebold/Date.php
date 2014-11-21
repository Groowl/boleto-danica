<?php

class Sebold_Date {
	
	public static function humanDateDiff(Zend_Date $date) {
		$currentDate = Zend_Date::now();
		
		$dateDiff = $currentDate->sub($date)->get(Zend_Date::TIMESTAMP);
		
		if ($dateDiff < 60) {
			return $dateDiff . ' segundos atrás';
		} elseif ($dateDiff < 3600) {
			return floor($dateDiff/60) . ' minutos atrás';
		} elseif ($dateDiff < 86400) {
			return floor(($dateDiff/60)/24) . ' horas atrás';
		} elseif ($dateDiff < (86400*30)) {
			return floor((($dateDiff/60)/24)/30) . ' dias atrás';
		}
		
	}
	
}