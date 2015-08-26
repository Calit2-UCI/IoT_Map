<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_sorter($params, &$tpl)
{
	$tpl->CI->load->helper('start');
	return sorter($params);
}




