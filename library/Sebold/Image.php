<?php

class Sebold_Image {
	
	public $Nome;
	public $arquivo;
	public $nomeArquivo;
	public $extensao;
	public $Im;
	public $Width;
	public $Height;
	public $ftpHost;
	public $ftpLogin;
	public $ftpPassword;
	public $ftpRoot;
	public $pasta;
	
	
	function CriarImagem($arquivo) {
		
		$this->arquivo = $arquivo['tmp_name'];
	
		//print_r($arquivo); exit();
		$this->nomeArquivo = $arquivo['name'];
		
		switch(strtolower(substr($this->nomeArquivo, -3, 3))) {
			case "gif":
				$this->Im = imagecreatefromgif($this->arquivo);
			break;
			case "jpg":
				$this->Im = imagecreatefromjpeg($this->arquivo);
			break;
			case "png":
				$this->Im = imagecreatefrompng($this->arquivo);
			break;
			
		}
		
		
		$ar = explode('.', $this->nomeArquivo);

		$this->Nome    = $ar[0];
		$this->Width   = imagesx($this -> Im);
		$this->Height  = imagesy($this -> Im);
		
	}
	
	function CriarImagemStrangeMode($arquivo) {
		
		$arr = explode('/', $arquivo['tmp_name']);
		
		$this->arquivo = str_replace($arr[count($arr) - 1], $arquivo['name'], $arquivo['tmp_name']);
		
		//print_r($this->arquivo); //exit();
		$this->nomeArquivo = $arquivo['name'];
		
		switch(strtolower(substr($this->nomeArquivo, -3, 3))) {
			case "gif":
				$this->Im = imagecreatefromgif($this->arquivo);
			break;
			case "jpg":
				$this->Im = imagecreatefromjpeg($this->arquivo);
			break;
			case "png":
				$this->Im = imagecreatefrompng($this->arquivo);
			break;
		}
		
		
		$ar = explode('.', $this->nomeArquivo);

		$this->Nome    = $ar[0];
		$this->Width   = imagesx($this -> Im);
		$this->Height  = imagesy($this -> Im);
		
	}
	
	
	function AlterarNome($nome) {
		$this->Nome = $nome;
	}
	
	
	function CriarThumb($destino, $x, $y, $mode, $qualidade = 80) {
		if ($mode === 'c') {
		     //getting the image dimensions
		     $thumbnail_width = $x;
		     $thumbnail_height = $y; 
		    $width_orig = $this->Width;
		    $height_orig = $this->Height;
		    
            $ratio_orig = $width_orig/$height_orig;
            
            if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
               $new_height = $thumbnail_width/$ratio_orig;
               $new_width = $thumbnail_width;
            } else {
               $new_width = $thumbnail_height*$ratio_orig;
               $new_height = $thumbnail_height;
            }
            
            $x_mid = $new_width/2;  //horizontal middle
            $y_mid = $new_height/2; //vertical middle
            
            
            $process = imagecreatetruecolor(round($new_width), round($new_height)); 
            imagecopyresampled($process, $this->Im, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
            $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
            imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);
		} elseif ( $mode == 'p' ) {
			if( ($this -> Width / $x) > ($this -> Height / $y) )
				$fator = $this -> Width / $x;
			else
				$fator = $this -> Height / $y;
			$largura = $this -> Width / $fator;
			$altura  = $this -> Height / $fator;
			
    		$thumb = imagecreatetruecolor($largura, $altura);
    		imagecopyresampled($thumb, $this -> Im, 0, 0, 0, 0, $largura, $altura, $this -> Width, $this -> Height);
		} elseif ( $mode == 'e' ) {
			$largura = $x;
			$altura  = $y;
			
    		$thumb = imagecreatetruecolor($largura, $altura);
    		imagecopyresampled($thumb, $this -> Im, 0, 0, 0, 0, $largura, $altura, $this -> Width, $this -> Height);
		}
		
		
		
		switch(strtolower(substr($this->Nome, -3, 3))) {
			case "gif":
				imagegif($thumb, $destino . $this->Nome);
			break;
			case "jpg":
				imagejpeg($thumb, $destino . $this->Nome, $qualidade);
			break;
			case "png":
				imagepng($thumb, $destino . $this->Nome, $qualidade);
			break;
		}
		
		imagedestroy($thumb);
		
	}
	
	function getWidth() {
		return $this -> Width;
	}
	
	function getHeight() {
		return $this -> Height;
	}
	
	function ApagarImagens() {
		imagedestroy($this->Im);
	}
	

}

?>