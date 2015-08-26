<?php
/**
 * Template_Lite {helper} function plugin
 *
 * Type:     function
 * Name:     module_tpl
 * Purpose:  Shows template if module is installed
 * Input:
 *           - module = the name of the module in which tpl will be searched
 *           - module_1, module_2... = additional modules to check
 *           - tpl = the value of the hidden field
 *           - theme_type = user/admin
 */

function tpl_function_module_tpl($params, &$tpl)
{
	if(empty($params['module']) || empty($params['tpl'])){
		return false;
	}
	$params['module'] = strtolower($params['module']);
	if(!$tpl->CI->pg_module->is_module_installed($params['module'])){
		return '';
	}
	// Pass through additional modules and check if they are installed
	for($i = 1;;$i++) {
		$key = 'module_' . $i;
		if(empty($params[$key]) ) {
			// No additional modules
			break;
		} else {
			if(!$tpl->CI->pg_module->is_module_installed($params[$key])){
				return '';
			}
		}
	}

	if(empty($params['theme_type'])) {
		$params['theme_type'] = 'user';
	} elseif('user'!== $params['theme_type'] && 'admin' !== $params['theme_type']) {
		$params['theme_type'] = 'user';
		log_message('ERROR', 'Wrong theme type, "user" used');
	}

	return $tpl->CI->template_lite->fetch($params['tpl'], $params['theme_type'], $params['module']);

}
