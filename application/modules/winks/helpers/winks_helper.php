<?php 

if (!defined('BASEPATH'))	{
	exit('No direct script access allowed');
}

if (!function_exists('wink')) {

	function wink($params) {
		$CI = &get_instance();
		if('user' !== $CI->session->userdata('auth_type')) {
			return '';
		}
		$current_user = (int)$CI->session->userdata('user_id');
		$target_user = (int)$params['user_id'];
		if(!$current_user || !$target_user || $current_user === $target_user) {
			return '';
		}

		$CI->load->model('Winks_model');
		$wink = $CI->Winks_model->get_by_pair($current_user, $target_user);
        $is_pending = false;
		if($wink) {
			if((int)$wink['id_from'] === $current_user) {
                $is_pending = true;
			} else {
				$CI->template_lite->assign('wink_back', true);
			}
		} else {
			$CI->template_lite->assign('is_new', true);
		}
        $CI->template_lite->assign('is_pending', $is_pending);
		$CI->template_lite->assign('wink', $wink);
		$CI->template_lite->assign('current_id', $current_user);
		$CI->template_lite->assign('partner_id', $target_user);
        $CI->template_lite->assign('wink_button_rand', rand(100000, 999999));
		$CI->template_lite->view('helper_wink', 'user', 'winks');
	}

}

if(!function_exists('winks_count')) {

	function winks_count($attrs) {
		$CI = & get_instance();
		$CI->load->model('Winks_model');
		$winks = $CI->Winks_model->backend_winks_count();
		$CI->template_lite->assign('winks_count', $winks['count']);
		return $CI->template_lite->fetch('helper_winks_' . $attrs['template'], 'user', 'winks');
	}

}