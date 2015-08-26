<?php

function tpl_function_ld_option($params, &$tpl)
{
    if (!isset($params['lang_id'])) $params['lang_id'] = '';
    if (!isset($params['default'])) $params['default'] = '';
    
	return ld_option($params['i'], $params['gid'], $params['option'], $params['lang_id'], $params['default']);
}
