<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_seotag($params, &$tpl)
{
	$tpl->CI->load->helper('seo');
	return seo_tags($params['tag']);
}
