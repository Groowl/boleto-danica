<?php


class Fonix_Filter_Censura implements Zend_Filter_Interface{
	
	protected $texto;
	protected $db;
	protected $replacement;
	protected $campo;
	
	public function __construct($texto, $tabela, $campo, $replacement){
		$this->texto = $texto;
		$this->db = $tabela;
		$this->campo = $campo;
		$this->replacement = $replacement;
	}
	
    public function filter($value){
    	
    	$dbSel = $this->db->select();
    	$palavras = $this->db->fetchAll($dbSel)->toArray();
		
		foreach ($palavras as $key => $palavras) {
			$value = preg_replace($palavras[$this->campo], $this->replacement, $value);
		}
		
		return $value;
    }
    
	public static function filtrar($texto, $tabela, $campo, $replacement){
    	
    	$dbSel = $tabela->select();
    	$palavras = $tabela->fetchAll($dbSel)->toArray();
		
		$value = $texto;
		
		foreach ($palavras as $key => $palavras) {
			$value = preg_replace('/ '.$palavras[$campo].' /', $replacement, $value);
		}
		
		return $value;
    }
    
}

