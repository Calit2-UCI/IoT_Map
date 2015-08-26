<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('assign_user_session')){
	function assign_user_session(){
		if(INSTALL_MODULE_DONE){
			$CI = &get_instance();
			$CI->template_lite->assign('user_session_data', $CI->session->all_userdata());
			$CI->template_lite->assign('js_events', $CI->session->flashdata('js_events'));
		}
		return;
	}
}
