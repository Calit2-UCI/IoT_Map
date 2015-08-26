<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_vehicle_color_select($params, &$tpl)
{
	$tpl->CI->load->helper('vehicle_properties');
	return color_select($params['selected'], $params['max'], $params['var_name']);
}
