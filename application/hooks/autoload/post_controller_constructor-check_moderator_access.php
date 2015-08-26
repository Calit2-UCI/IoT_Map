<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('check_moderator_access')){

	function check_moderator_access(){
		if(INSTALL_MODULE_DONE){

			$CI = &get_instance();
			
			$controller = $CI->router->fetch_class(true);
			if(substr($controller, 0, 6) != "admin_") return;
			
			$auth_type = $CI->session->userdata("auth_type");
			if($auth_type != "admin") return;
			
			$user_type = $CI->session->userdata("user_type");
			if($user_type != "moderator") return;

			$module = $CI->router->fetch_class();
			if($module == "start") return;
			
			$method = $CI->router->fetch_method();
			
			if($module == 'ausers' && $method == 'logoff') return;
			
			$CI->load->model('Moderators_model');
			$methods = $CI->Moderators_model->get_module_methods($module);	
			if(is_array($methods) && !in_array($method, $methods)) return;
			
			$permission_data = $CI->session->userdata("permission_data");
			if(!isset($permission_data[$module][$method]) || $permission_data[$module][$method] != 1 ){
				$url = site_url()."admin/start/error/moderator";
				redirect($url);
			}
		}
		return;
	}

}
