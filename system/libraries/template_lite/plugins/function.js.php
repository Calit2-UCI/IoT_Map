<?php
/**
 * Template_Lite {js} function plugin
 *
 * Type:     function
 * Name:     js
 * Purpose:  loads javascript file
 * Input:
 *           - module = module name
 *           - file = file name
 */

function tpl_function_js($params, &$tpl)
{
	if(empty($params['file'])) {
		log_message('error', 'Empty js file name');
		$tpl->trigger_error("js plugin: Empty file name");
	}
	$path = '/' . SITE_SUBFOLDER . APPLICATION_FOLDER;
	if(!empty($params['module'])) {
		if('install' === $params['module'] || (INSTALL_MODULE_DONE && $tpl->CI->pg_module->is_module_installed($params['module']))) {
			$path .= 'modules/' . $params['module'] . '/js/';
		} else {
			return false;
		}
	} else {
		$path .= 'js/';
	}

	// Add an extension if necessary
	if('.js' != substr($params['file'], strlen($params['file']) - 3, 3)) {
		$params['file'] .= '.js';
	}
	if(!empty($params['return']) && $params['return'] == 'path'){
		return $path . $params['file'];
	}else{
		return '	<script type="text/javascript" src="' . $path . $params['file'] . '"></script>'."\n";
	}
}
