<?php
/**
 * Hook
 * set default css and js files
 */
if ( ! function_exists('seo_replace_vars')){

	function seo_replace_vars(){
		if(INSTALL_MODULE_DONE){

			$CI = &get_instance();
			$segment_count = count($CI->uri->rsegments);

			if($CI->pg_seo->use_seo_links_rewrite && $segment_count > 2){
				$module = $CI->router->fetch_class();
				$controller = $CI->router->fetch_class(true);
				$method = $CI->router->fetch_method();
				$controller_type = (substr($controller, 0, 6) == "admin_")?"admin":"user";

				//// is module uses seo links?
				$module_data = $CI->pg_seo->get_seo_module_by_gid($module);

				if(!empty($module_data)){
					//// get default data
					$settings = $CI->pg_seo->get_default_settings($controller_type, $module, $method);

					//// if this method get rewrite params

					if(!empty($settings["url_vars"])){
						$index = 3;
						foreach($settings["url_vars"] as $general_var => $replaces){
							$vars[$index] = $general_var;
							$index++;
						}
						
						if(!empty($settings["url_postfix"])){
							foreach($settings["url_postfix"] as $general_var => $replaces){
								$vars[$index] = $general_var;
								$index++;
							}
						}

						for($i=3; $i<=$segment_count; $i++){
							$temp = $CI->uri->rsegments[$i];
							$temp_array = explode(":", $temp);
							if(count($temp_array) > 1){
								$var_name = $temp_array[0];
								$var_value = $temp_array[1];
								$CI->uri->rsegments[$i] = $CI->pg_seo->get_module_rewrite_var($controller_type, $module, $method, $var_name, $vars[$i], $var_value);
							}
						}
					}elseif($settings["url_postfix"]){
						$index = 3;
						
						foreach($settings["url_postfix"] as $general_var => $replaces){
							$vars[$index] = $general_var;
							$index++;
						}

						for($i=3; $i<=$segment_count; $i++){
							$temp = $CI->uri->rsegments[$i];
							$temp_array = explode(":", $temp);
							if(count($temp_array) > 1){
								$var_name = $temp_array[0];
								$var_value = $temp_array[1];
								$CI->uri->rsegments[$i] = $CI->pg_seo->get_module_rewrite_var($controller_type, $module, $method, $var_name, $vars[$i], $var_value);
							}
						}
					}
				}
			}
		}
	}
}
