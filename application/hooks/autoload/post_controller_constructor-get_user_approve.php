<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('get_user_approve')) {

	function get_user_approve() {
		if (INSTALL_MODULE_DONE) {
			$CI = &get_instance();
			$not_approved_methods = array(
				'change_language',
				'confirm',
				'login',
				'login_form',
				'logout',
				'registration',
				'restore',
				'homepage',
				'account',
				'form',
			);
			if ($CI->pg_module->is_module_installed('users') && !$CI->session->userdata('approved') && $CI->session->userdata('auth_type') == 'user' && !in_array($CI->router->method, $not_approved_methods)) {
				$user_id = $CI->session->userdata('user_id');
				$use_approve = $CI->pg_module->get_module_config('users', 'user_approve');
				$CI->load->model('users/models/Users_model');
				if ($use_approve == 2 && $CI->pg_module->is_module_installed('services')) {
					$CI->load->model('services/models/Services_users_model');
					$service_access = $CI->Services_users_model->is_service_access($user_id, 'admin_approve');
					if ($service_access['use_status']) {
						if ($CI->router->class != 'services') {
							if($CI->router->is_api_class) {
								exit(json_encode(array(
									'data' => 'service_required', 
									'code' => 403,
									'system_messages' => array('errors' => l('error_approve_need_buy', 'users'))
								)));
							} else {
								$CI->system_messages->add_message('error', l('error_approve_need_buy', 'users'));
								redirect(site_url() . 'services/form/admin_approve');
							}
						}
						return;
					} elseif ($CI->router->method != 'settings') {
						if($CI->router->is_api_class) {
							exit(json_encode(array(
								'data' => 'waiting_for_approval', 
								'code' => 403,
								'system_messages' => array('errors' => l('error_approve_need_wait', 'users'))
							)));
						} else {
							$CI->system_messages->add_message('error', l('error_approve_need_wait', 'users'));
							redirect(site_url() . 'users/settings');
						}
						return;
					}
				} elseif ($use_approve == 1 && $CI->router->method != 'account') {
					if($CI->router->is_api_class) {
						exit(json_encode(array(
							'data' => 'waiting_for_approval', 
							'code' => 403,
							'system_messages' => array('errors' => l('error_approve_need_wait', 'users'))
						)));
					} else {
						$CI->system_messages->add_message('error', l('error_approve_need_wait', 'users'));
						redirect(site_url() . 'users/account');
					}
					return;
				} elseif (!$use_approve && $CI->router->method != 'settings') {
					if($CI->router->is_api_class) {
						exit(json_encode(array(
							'data' => 'waiting_for_approval', 
							'code' => 403,
							'system_messages' => array('errors' => l('error_approve_need_wait', 'users'))
						)));
					} else {
						$CI->system_messages->add_message('error', l('error_approve_need_wait', 'users'));
						redirect(site_url() . 'users/logout');
					}
				}
			}
		}
		return;
	}

}
