<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_seolink($params, &$tpl)
{
	$tpl->CI->load->helper('seo');
	if(!empty($params['data'])){
		if (is_array($params['data']))  {
			$data = $params['data'];
		} else {
			$data['id'] = $params['data'];
		}
		$data = array_merge($data, $params);
	} else {
		$data = $params;
	}
	unset($data['module'], $data['method'], $data['data']);
	return rewrite_link($params['module'], $params['method'], $data);
}
