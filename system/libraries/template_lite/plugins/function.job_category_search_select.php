<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_job_category_search_select($params, &$tpl)
{
	$tpl->CI->load->helper('properties');

	return job_category_search_select($params['categories'], $params['var'], $params['var_js']);
}
