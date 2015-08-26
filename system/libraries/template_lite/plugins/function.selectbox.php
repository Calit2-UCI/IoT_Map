<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_selectbox($params, &$tpl)
{
	$tpl->CI->load->helper('start');
	return selectbox($params);
}


