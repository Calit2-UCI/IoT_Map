<?php if (!defined('BASEPATH'))	{ exit('No direct script access allowed'); }


if(!function_exists("kisses_list")){
	/**
	 * Show mark as spam button
	 * @param array $data
	 * @return html
	 */
	function kisses_list($data){
		$CI = & get_instance();
		
		if( empty($data["user_id"]) ) return '';
		
		$CI->template_lite->assign('user_id', $data["user_id"]);
		
		$user_id = $CI->session->userdata("user_id");
		if(!$user_id) {
			$CI->template_lite->assign('is_user', 0);
		} else {
			$CI->template_lite->assign('is_user', 1);
		}
		
		$CI->load->model('Kisses_model');
		$count = $CI->Kisses_model->get_count();
		if($count > 0){
            $CI->template_lite->assign('kisses_button_rand', rand(100000, 999999));
			return $CI->template_lite->fetch("helper_kisses_btn", "user", "kisses");
		} else {
			return;
		}
	}
	
	/**
	 * Echo count kisses
	 * @param array $data
	 * @return html
	 */
	if(!function_exists('new_kisses')) {
		
		function new_kisses($attrs) {
			$CI = & get_instance();
			if ('user' != $CI->session->userdata("auth_type")) {
				return false;
			}
			$user_id = $CI->session->userdata("user_id");
			if(!$user_id) {
				log_message('Empty user id');
				return false;
			}
			if(empty($attrs['template'])) {
				$attrs['template'] = 'header';
			}
			$CI->load->model('Kisses_model');
			$count = $CI->Kisses_model->new_kisses_count($user_id);
			
			//return $count;
			
			$CI->template_lite->assign('kisses_count', $count);
			return $CI->template_lite->fetch('helper_new_kisses_' . $attrs['template'], 'user', 'kisses');
		}
		
	}
}
