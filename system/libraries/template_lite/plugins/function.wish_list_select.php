<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_wish_list_select($params, &$tpl){
	$tpl->CI->load->helper("listings");
	return wish_list_select(
		isset($params["selected"]) ? $params["selected"] : array(), 
		isset($params["max"]) ? $params["max"] : 0, 
		isset($params["var_name"]) ? $params["var_name"] : "id_user", 
		isset($params["template"]) ? $params["template"] : "default", 
		array_diff_key($params, array_flip(array("selected", "max", "var_name", "template")))
	);
}
