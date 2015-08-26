<?php
/**
 * template_lite json_decode modifier plugin
 *
 * Type:     modifier
 * Name:     lower
 * Purpose:  Wrapper for the PHP 'json_decode' function
 */
function tpl_function_json_decode($params, &$tpl)
{
	return json_decode($params["data"]);
}
