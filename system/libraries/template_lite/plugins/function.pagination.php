<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_pagination($params, &$tpl)
{
	$tpl->CI->load->helper('start');
	return pagination($params);
}
