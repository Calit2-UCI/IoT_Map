<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_slider($params, &$tpl)
{
	$tpl->CI->load->helper('start');
	return slider($params);
}