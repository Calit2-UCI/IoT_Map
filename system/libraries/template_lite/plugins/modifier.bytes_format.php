<?php
/**
 * Template Lite plugin converted from Smarty
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty bytes_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     bytes_format<br>
 * Purpose:  bytes_format the string according to bytes_formatment type
 * @link http://smarty.php.net/manual/en/language.modifier.bytes_format.php
 *          bytes_format (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function tpl_modifier_bytes_format($string, $to = '', $from='b')
{
	switch($from){
		case "kb": $string = $string*1024; break;
		case "mb": $string = $string*1024*1024; break;
		case "gb": $string = $string*1024*1024*1024; break;
		case "b": 
		default: $string = $string*1; break;
	}
	$ret_arr["b"] = $string."b";
	$ret_arr["kb"] = round($string/1024, 1)."KB";
	$ret_arr["mb"] = round($string/(1024*1024), 1)."MB";
	$ret_arr["gb"] = round($string/(1024*1024*1024), 1)."GB";

	if($to != ''){
		return $ret_arr[$to];
	}else{
		if($ret_arr["gb"] > 1) return $ret_arr["gb"];
		if($ret_arr["mb"] > 1) return $ret_arr["mb"];
		if($ret_arr["kb"] > 1) return $ret_arr["kb"];
		 return $ret_arr["b"];
	}
}