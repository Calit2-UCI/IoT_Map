<?php
/**
 * Template_Lite {css} function plugin
 *
 * Type:     function
 * Name:     css
 * Purpose:  loads css file
 * Input:
 *           - media = media type
 *           - file = file name
 */

function tpl_function_css($params, &$tpl)
{
	if(empty($params['file'])) {
		log_message('error', 'Empty css file name');
		$tpl->trigger_error("css plugin: Empty file name");
	}
	
	if(empty($params['media'])) {
		$params['media'] = 'all';
	}

	// Add an extension if necessary
	if('.css' != substr($params['file'], strlen($params['file']) - 4, 4)) {
		$params['file'] .= '-[rtl].css';
	}
	
	$tpl->CI->pg_theme->add_theme_css($params['file'], $params['media']);
}
