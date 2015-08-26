<?php

if (!function_exists('packages_list')) {
	function packages_list($params = array()) {
		//{block name=packages_list module=packages}
		$CI = & get_instance();
		$CI->load->model('Packages_model');

		$where['where']['status'] = 1;
		if(!empty($params['gid'])){
			$where['where']['gid'] = $params['gid'];
		}
		if(!empty($params['id'])){
			$where['where']['id'] = $params['id'];
		}

		$CI->template_lite->assign('hide_btn', !empty($params['hide_btn']));
		$CI->template_lite->assign('stretch', !empty($params['stretch']));
		$packages = !empty($params['packages']) ? $params['packages'] : $CI->Packages_model->get_packages_list($where);
		
		if(empty($packages)) return '';
		
         if (!empty($params['headline'])) {
            $CI->template_lite->assign('headline', true);
        }
        
		$CI->template_lite->assign('block_packages', $packages);
		return $CI->template_lite->fetch('helper_packages_list', 'user', 'packages');
	}
}

if (!function_exists('user_packages_list')) {
	function user_packages_list($params = array()) {
		$CI = & get_instance();
		$id_user = $CI->session->userdata('auth_type') == 'user' ? $CI->session->userdata['user_id'] : false;
		if(!$id_user){
			return false;
		}
		$CI->load->model('Packages_model');
		$CI->load->model('packages/models/Packages_users_model');

		$where['where']['id_user'] = $id_user;
		$order_by['till_date'] = 'DESC';

		$CI->template_lite->assign('hide_btn', !empty($params['hide_btn']));
		$CI->template_lite->assign('stretch', !empty($params['stretch']));
		
		if(!empty($params['packages'])){
			$user_packages = $params['packages'];
		}else{
			$user_packages = $CI->Packages_users_model->get_user_packages_list($order_by, $where);
		}
		
		if(empty($user_packages)) return '';
		
		$sort_user_packages = array('active' => array(), 'inactive' => array());
		foreach($user_packages as $key => $user_package){
			if($user_package['is_active']){
				$sort_user_packages['active'][] = $user_package;
			}else{
				$sort_user_packages['inactive'][] = $user_package;
			}
		}
		$user_packages = array_merge($sort_user_packages['active'], $sort_user_packages['inactive']);

		$CI->template_lite->assign('block_user_packages', array_values($user_packages));

		$date_formats["date_format"] = $CI->pg_date->get_format('date_literal', 'st');
		$date_formats["date_time_format"] = $CI->pg_date->get_format('date_time_literal', 'st');
		$CI->template_lite->assign('block_user_packages_date_formats', $date_formats);

		return $CI->template_lite->fetch('helper_user_packages_list', 'user', 'packages');
	}
}
