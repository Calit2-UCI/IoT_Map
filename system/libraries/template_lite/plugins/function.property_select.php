<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_property_select($params, &$tpl)
{
	$tpl->CI->load->helper('properties');
	if(!isset($params["empty"])){
		$params["empty"] = 1;
	}
	if(!isset($params["multi"])){
		$params["multi"] = 0;
	}
	if(!isset($params["lang_id"])){
		$params["lang_id"] = '';
	}
	if(!isset($params["selected"])){
		$params["selected"] = array();
	}
	if(!isset($params["name"])){
		$params["name"] = $params["gid"];
	}
	return property_select($params['gid'], $params['name'], $params['selected'], $params['multi'], $params["empty"], $params['lang_id']);
}
