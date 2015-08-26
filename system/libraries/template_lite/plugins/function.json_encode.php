<?php
/**
 * template_lite json_encode modifier plugin
 *
 * Type:     modifier
 * Name:     lower
 * Purpose:  Wrapper for the PHP 'json_encode' function
 */
function tpl_function_json_encode($params, &$tpl)
{
	return json_encode($params["data"]);
}
