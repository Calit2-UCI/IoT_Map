<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_job_category_select($params, &$tpl)
{
	$tpl->CI->load->helper('properties');
	if(!$params['max']) $params['max'] = 5;
	if(!$params['level']) $params['level'] = 2;
	if(!$params['output']) $params['output'] = 'max';
	
	return job_category_select($params['max'], $params['categories'], $params['var'], $params['level'], $params['var_js'], $params['output']);
}
