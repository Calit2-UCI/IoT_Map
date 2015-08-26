<?php

class File_cache{

	private $cache_valid_timeout_default = 3600;
	private $cache_folder = "temp/cache/";

	function __construct(){
		$this->cache_folder = SITE_PATH.$this->cache_folder;
	}
	
	public function get_cache($key, $cache_valid_timeout=null){
		if($this->is_valid_cache($key, $cache_valid_timeout)){
			$file_name = $this->cache_folder.$key.".txt";
			$data = file_get_contents($file_name);
			return unserialize($data);
		}else{
			return false;
		}
	}

	public function set_cache($key, $value){
		$file_name = $this->cache_folder.$key.".txt";
		$data = serialize($value);
		$h = fopen($file_name, "w");
		fwrite($h, $data);
		fclose($h);
		return;
	}
	
	public function is_valid_cache($key, $cache_valid_timeout=null){
		$file_name = $this->cache_folder.$key.".txt";

		if(!file_exists($file_name)){
			return 0;
		}

		$file_stat = stat($file_name);
		if(empty($cache_valid_timeout)) $cache_valid_timeout = $this->cache_valid_timeout_default;

		if($file_stat["mtime"] + $cache_valid_timeout < time()){
			$this->delete_cache($key);
			return 0;
		}

		return 1;
	}

	public function delete_cache($key){
		$file_name = $this->cache_folder.$key.".txt";
		if(file_exists($file_name)){
			unlink($file_name);
		}
	}

	public function get_cache_key($str){
		if(is_array($str)){
			$str = serialize($str);
		}
		return md5($str);
	}
}