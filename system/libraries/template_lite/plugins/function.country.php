<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_country($params, &$tpl)
{
	$tpl->CI->load->helper('countries');
	return country($params['id_country'], $params['id_region'], $params['id_city']);
}
