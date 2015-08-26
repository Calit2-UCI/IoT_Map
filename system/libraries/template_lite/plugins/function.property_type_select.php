<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_property_type_select($params, &$tpl)
{
	$tpl->CI->load->helper('listings');
	if(!$params['max']) $params['max'] = 1;
	if(!$params['level']) $params['level'] = 2;
	if(!$params['output']) $params['output'] = 'max';
	
	return property_type_select($params['max'], $params['property_types'], $params['var'], $params['level'], $params['var_js'], $params['output']);
}
