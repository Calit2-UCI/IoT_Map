<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_geomap_amenity_select($params, &$tpl)
{
	$tpl->CI->load->helper('geomap');

	if(!$params['max']) $params['max'] = 10;
	if(!$params['output']) $params['output'] = 'max';
	
	return geomap_amenity_select($params['gid'], $params['max'], $params['amenities'], $params['var'], $params['var_js'], $params['output']);
}
