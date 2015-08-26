<?php
/**
 * Template_Lite  function plugin
 *
 */
function tpl_function_var_dump($params, &$tpl)
{
	$dump = '<pre>'.print_r($params['var'], true).'</pre>';
	return nl2br($dump);
}