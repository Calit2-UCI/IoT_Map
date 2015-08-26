<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_checkbox($params, &$tpl)
{
	$tpl->CI->load->helper('start');
	return checkbox($params);
}



