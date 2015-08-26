<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_menu($params, &$tpl)
{
	$tpl->CI->load->helper('menu');
	return get_menu($params['gid'], isset($params['template']) ? $params['template'] : array());
}

