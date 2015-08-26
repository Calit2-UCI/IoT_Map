<?php

/**
 * Template_Lite {helper} function plugin
 *
 * Type:     function
 * Name:     helper
 * Purpose:  Execute helper function
 * Input:
 *           - func_name = helper function name
 *           - helper_name = helper name (required if helper is not in autoloaded)
 */
function tpl_function_helper($params, &$tpl) {
	if (isset($params['cache'])) {
		require_once(TEMPLATE_LITE_DIR . 'internal/template.get_cache_file.php');
		$_params['cache_tpl_name'] = $tpl->_cache_salt;
		$_params['cache_tpl_value'] = $params['cache'];
		template_get_cache_file($_params, $tpl);
	} else {
		if (isset($params['module'])) {
			$params['module'] = strtolower($params['module']);
			if (!INSTALL_MODULE_DONE || !$tpl->CI->pg_module->is_module_installed($params['module'])) {
				return '';
			} elseif (empty($params['helper_name'])) {
				$params['helper_name'] = $params['module'];
			}
		}

		if (!function_exists($params['func_name']) && !empty($params['helper_name'])) {
			$tpl->CI->load->helper($params['helper_name'], isset($params['module']) ? $params['module'] : null);
		}

		if (!function_exists($params['func_name'])) {
			return '';
		} elseif (!empty($params['func_param'])) {
			return $params['func_name']($params['func_param']);
		} else {
			return $params['func_name']();
		}
	}
}
