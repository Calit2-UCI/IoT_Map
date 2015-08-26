<?php
/**
 * template_lite translit modifier plugin
 *
 * Type:     modifier
 * Name:     truncate
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and 
 *           appending the $etc string.
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 */
function tpl_modifier_translit($string)
{
	if(!$string) return '';

	$CI = get_instance();
	$CI->load->library('Translit');
	
	$current_lang = $CI->pg_language->get_lang_by_id($CI->pg_language->current_lang_id);
	$current_lang_code = $current_lang['code'];

	$string = strip_tags($string);
	$string = $CI->translit->convert($current_lang_code, $string);
	$string = preg_replace("/[\n\s\t\-]+/i", '_', $string);
	$string = preg_replace("/[^A-Za-z0-9_]+/i", '', $string);
	
	return $string;
}
