<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!function_exists('show_social_networking_link')) {

	function show_social_networking_link() {
		$CI = & get_instance();
		$user_id = $CI->session->userdata('user_id');
		$CI->load->model('social_networking/models/Social_networking_services_model');
		$CI->load->model('users_connections/models/Users_connections_model');
		$services = $CI->Social_networking_services_model
				->get_services_list(null, array('where' => array('oauth_status' => 1)));
		$apps = array();
		$unapps = array();
		foreach ($services as $id => $val) {
			$connection = $CI->Users_connections_model->get_connection_by_user_id($val['id'], $user_id);
			if ($connection && isset($connection['id'])) {
				$unapps[$id] = $val;
			} else {
				$apps[$id] = $val;
			}
		}
		$CI->template_lite->assign("applications", $apps);
		$CI->template_lite->assign("un_applications", $unapps);
		$CI->template_lite->assign("site_url", site_url());
		echo $CI->template_lite->fetch("oauth_link", 'user', 'users_connections');
	}

}

if (!function_exists('show_social_networking_login')) {

	function show_social_networking_login() {
		//$domain = $_SERVER['SERVER_NAME'];
		$CI = & get_instance();
		$CI->load->model('social_networking/models/Social_networking_services_model');
		$services = $CI->Social_networking_services_model->get_services_list(null, array('where' => array('oauth_status' => 1)));
		$CI->template_lite->assign("services", $services);
		$CI->template_lite->assign("site_url", site_url());
		return $CI->template_lite->fetch('oauth_login', 'user', 'users_connections');
	}

}