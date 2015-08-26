<?php
/**
 * Template_Lite {ld} function plugin
 *
 * Type:     function
 * Name:     ld
 * Purpose:  Gets names of variables in data sources for multilang purposes
 * Input:
 *           - gid
 *           - i = the shortname of the variable 
 *           
 * Author:   Ruslan Abramov
 */
function tpl_function_ld($params, &$tpl)
{
	$assign = (!empty($params['assign']))?$params['assign']:'ld_'.$params['i'];
	$assign = str_replace(array(' ', '-'), '_', $assign);
	$assign = preg_replace('/[^a-z0-9_]/i', '', $assign);	
	
	$ld = ld($params['i'], $params['gid']);
	$tpl->assign($assign, $ld);
	return '';
}
