<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('blacklist_button')) {

	function blacklist_button($params) {
		$CI = &get_instance();
		$CI->load->model('Blacklist_model');
		if (!isset($params['id_user']) || empty($params['id_user'])) {
			return '';
		}
		$user_id = $CI->session->userdata('user_id');
		if ($user_id && in_array($params['id_user'], $CI->Blacklist_model->get_list_users_ids($user_id))) {
			$action = 'remove';
		} else {
			$action = 'add';
		}
		$CI->template_lite->assign('action', $action);
		$CI->template_lite->assign('id_dest_user', $params['id_user']);
		$tpl = $CI->template_lite->fetch('helper_blacklist', 'user', 'blacklist');
		return $tpl;
	}

}