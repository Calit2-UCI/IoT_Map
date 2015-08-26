<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_hlbox($params, &$tpl)
{
	$tpl->CI->load->helper('start');
	return hlbox($params);
}