<?php

if ( ! function_exists('seo_tags'))
{
	function seo_tags($tags='title')
	{
		$CI = &get_instance();

		$controller = $CI->router->fetch_class(true);
		if(substr($controller, 0, 6) == "admin_"){
			$module_gid = strtolower(substr($controller, 6));
			$controller = "admin";
		}else{
			$module_gid = strtolower($controller);
			$controller = "user";
		}
		$method = $CI->router->fetch_method();

		$html = $CI->pg_seo->session_seo_tags_html($controller, $module_gid, $method);

		$return = '';
		$tags_array = explode("|", $tags);
		if(!empty($tags_array)){
			foreach($tags_array as $tag){
				$tag = trim(strtolower($tag));
				if(!empty($html[$tag])){
					$return .= $html[$tag];
				}
			}
		}

		echo $return;
	}

}

if ( ! function_exists('seo_tags_default'))
{
	function seo_tags_default($tags='title')
	{
		$CI = &get_instance();

		$html = $CI->pg_seo->session_seo_tags_html('user', 'start', 'index');

		$return = '';
		$tags_array = explode("|", $tags);
		if(!empty($tags_array)){
			foreach($tags_array as $tag){
				$tag = trim(strtolower($tag));
				if(!empty($html[$tag])){
					$return .= $html[$tag];
				}
			}
		}

		echo $return;
	}
}

if ( ! function_exists('rewrite_link'))
{
	function rewrite_link($module, $method, $data=array(), $is_admin=false){
		$CI = &get_instance();
		return $CI->pg_seo->create_url($module, $method, $data, $is_admin);
	}
}
