<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_user_select($params, &$tpl){
	$tpl->CI->load->helper('users');
	return user_select(
		isset($params['selected']) ? $params['selected'] : array(), 
		isset($params['max']) ? $params['max'] : 0, 
		isset($params['var_name']) ? $params['var_name'] : 'id_user', 
		$params['user_type'],
		isset($params['template']) ? $params['template'] : 'default', 
		array_diff_key($params, array_flip(array('selected', 'max', 'var_name', 'user_type', 'template')))
	);
}
