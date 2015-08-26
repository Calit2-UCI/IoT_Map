<?php
/** 
 * template_lite {l} plugin 
 * 
 * Type:     function 
 * Name:     l
 * Purpose:  get language string by key ($params[i]) and gid, if it is set (params[gid])
 */
function tpl_compiler_l($params, &$tpl)
{
	extract($params);
		
	if (isset($params['i']) && !empty($params['i'])) 
	{
		$i = $params['i'];
	}	
	else 
	{
		$tpl->trigger_error("missing 'i' (name of the language variable) attribute in 'l'", E_USER_ERROR, __FILE__, __LINE__);
	}
	if (isset($params['i'])) unset($params['i']);
	
	if (isset($params['assign']) && !empty($params['assign'])) 
	{
		$assign_var = $params['assign'];
	}
	if (isset($params['assign'])) unset($params['assign']);

	if (isset($params['gid']) && !empty($params['gid'])){
		$gid = $params['gid'];
	}else{
		$gid = "start";
	}
	if (isset($params['gid'])) unset($params['gid']);
 
	if (isset($params['lang']) && !empty($params['lang'])){
		$lang_id = $params['lang'];
	}else{
		$lang_id = "''";
	}
	if (isset($params['lang'])) unset($params['lang']);

	if (isset($params['type']) && !empty($params['type'])){
		$edit_type = $params['type'];
	}else{
		$edit_type = "'text'";
	}
	if (isset($params['type'])) unset($params['type']);

	//if (isset($params['replace_array'])) $params = array_merge($params['replace_array'], $replace_array);

	$replace_array = array();
	foreach($params as $param_name=>$param_value){
		if($param_name == 'replace_array') continue;
		$replace_array[] = "'".str_replace("'", "\'", $param_name)."'=>".$param_value;
	}
	$replace_array_str = 'array('.implode(',', $replace_array).')';

	if(isset($params['replace_array'])){
		$replace_array_str = 'array_merge('.$replace_array_str.','.$params['replace_array'].')';
	}

	$output = 'l('.$i.', '.$gid.', '.$lang_id.', '.$edit_type.', '.$replace_array_str.')';

	if (isset($assign_var))
	{
		$output = "\n\$this->assign(" . $assign_var . ", " .$output. ");\n";		
	}
	else 
	{
		$output = 'echo '.$output.';';
	}
	
	return $output;
}
