<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('friendlist_links')) {

	function friendlist_links($params) {
		$id_dest_user = filter_var($params['id_user'], FILTER_VALIDATE_INT);
		if (!$id_dest_user) {
			return '';
		}
		$CI = & get_instance();
		$id_user = $CI->session->userdata('user_id');
		if ($id_dest_user && $CI->session->userdata('auth_type') === 'user' && $id_user != $id_dest_user) {
			$CI->load->model('Friendlist_model');
			$statuses = $CI->Friendlist_model->get_statuses($id_user, $id_dest_user);
			$buttons = array();
			foreach ($statuses['allowed_btns'] as $btn => $params) {
				if ($params['allow']) {
					$buttons[$btn] = $params;
				}
			}
			$CI->template_lite->assign('id_user', $id_user);
			$CI->template_lite->assign('id_dest_user', $id_dest_user);
			$CI->template_lite->assign('buttons', $buttons);
		}
        $CI->template_lite->assign('friendlist_button_rand', rand(100000, 999999));
		return $CI->template_lite->fetch('helper_lists_links', 'user', 'friendlist');
	}

}

if (!function_exists('friend_input')) {

	function friend_input($params) {
		$CI = &get_instance();
		$CI->load->model('Users_model');
		$CI->load->model('Friendlist_model');

		$user_id = $CI->session->userdata('user_id');

		$friends_ids = $CI->Friendlist_model->get_friendlist_users_ids($user_id);
		if (empty($friends_ids)) {
			return '';
		}

		if (!isset($params['id_user']) && !empty($params['id_user'])) {
			$data['user'] = $CI->Users_model->get_user_by_id($params['id_user']);
		}

		$data['var_user_name'] = isset($params['var_user_name']) ? $params['var_user_name'] : 'id_user';
		$data['var_js_name'] = isset($params['var_js_name']) ? $params['var_js_name'] : '';
		$data['placeholder'] = isset($params['placeholder']) ? $params['placeholder'] : '';
		$data['values_callback'] = isset($params['values_callback']) ? $params['values_callback'] : '';

		$data['rand'] = rand(100000, 999999);

		$CI->template_lite->assign('friend_helper_data', $data);
		return $CI->template_lite->fetch('helper_friend_input', 'user', 'friendlist');
	}

}

if (!function_exists('friend_select')) {

	function friend_select($params) {
		$CI = & get_instance();
		$CI->load->model('Users_model');
		$CI->load->model('Friendlist_model');

		$user_id = $CI->session->userdata('user_id');
		$friends_ids = $CI->Friendlist_model->get_friendlist_users_ids($user_id);
		if (empty($friends_ids)) {
			return '';
		}

		if (isset($params['id_user']) && !empty($params['id_user'])) {
			$data['user'] = $CI->Users_model->get_user($params['id_user']);
		}

		$data['var_user_name'] = isset($params['var_user_name']) ? $params['var_user_name'] : 'id_user';
		$data['var_js_name'] = isset($params['var_js_name']) ? $params['var_js_name'] : '';

		$data['rand'] = rand(100000, 999999);

		$CI->template_lite->assign('friend_helper_data', $data);
		return $CI->template_lite->fetch('helper_friend_select', 'user', 'friendlist');
	}

}

if (!function_exists('friend_requests')) {

	function friend_requests($attrs) {
		$CI = & get_instance();
		$CI->load->model('Friendlist_model');
		$count = $CI->Friendlist_model->get_list_count((int) $CI->session->userdata('user_id'), 'request_in');
		$CI->template_lite->assign('friend_requests_count', $count);
		return $CI->template_lite->fetch('helper_friend_requests_' . $attrs['template'], 'user', 'friendlist');
	}

}

if (!function_exists('add_friendlist_button')) {

	function add_friendlist_button($params) {
		$CI = &get_instance();
		$CI->load->model('Friendlist_model');
		if (!isset($params['id_user']) || empty($params['id_user'])) {
			return '';
		}
		$user_id = $CI->session->userdata('user_id');
		$blacklist_ids = $CI->Friendlist_model->get_list_users_ids($user_id);
		if (in_array($params['id_user'], $blacklist_ids)) {
			return '';
		}
		$CI->template_lite->assign('id_dest_user', $params['id_user']);
		return $CI->template_lite->fetch('helper_add_friendlist', 'user', 'friendlist');
	}

}