<?php


class Translit{
	
	private $table = array(); 
	
	function __construct(){
		
	}
	
	public function convert($lang, $string){
		$tbl = $this->load_table($lang);
		if(empty($tbl)) return $string;
		
//		$temp=iconv("UTF-8","UTF-8//IGNORE",strtr($string,$tbl));
		$temp = str_replace(array_keys($tbl), array_values($tbl), $string); 
		
		return $temp;		
	}
	
	private function load_table($lang){
		$lang = strip_tags(trim($lang));
		
		if(empty($this->table[$lang])){
		
			$file = LIBPATH . 'Translit/'.$lang.'.php';
			if(file_exists($file)){
				include $file;
				$this->table[$lang] = $table;
			}
		
		}
		if(isset($this->table[$lang])) {
			return $this->table[$lang];
		} else {
			return null;
		}
	}
	
	public function detect_lang($str){
		
	}
	
}