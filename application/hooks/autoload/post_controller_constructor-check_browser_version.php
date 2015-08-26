<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('check_browser_version')){

	function check_browser_version(){
		if(INSTALL_MODULE_DONE){
			$CI = &get_instance();
			$CI->load->library('user_agent');
			if ($CI->agent->is_browser()){
				$CI->config->load('available_browsers', TRUE);
				$version = $CI->config->item($CI->agent->browser(), 'available_browsers');
				if ($CI->agent->version() < $version){
					$CI->template_lite->assign('display_browser_error', 1);
					//$CI->session->set_userdata("already_display_browser_error", '1');
				}
			}
		}
		return;
	}

}
