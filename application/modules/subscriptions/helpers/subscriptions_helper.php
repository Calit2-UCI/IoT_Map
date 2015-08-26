<?php

if ( ! function_exists('get_user_subscriptions_form'))
{
	function get_user_subscriptions_form($place){
		$CI = & get_instance();
		$CI->load->model('Subscriptions_model');
		$CI->load->model('subscriptions/models/Subscriptions_users_model');
		$subscriptions_list = $CI->Subscriptions_model->get_subscriptions_list(null, null, null, array('where' => array('subscribe_type' => 'user')));
		if(empty($subscriptions_list)){
			return '';
		}
		$user_subscription = array();
		if(isset($_REQUEST['user_subscriptions_list'])){
			$user_s = $_REQUEST['user_subscriptions_list'];
			foreach ($user_s as $key => $value) {
				$user_subscription[$value]  = 1;
			}
		}else{
			$user_id = $CI->session->userdata('user_id');
			if ($user_id){
				$user_subscription = $CI->Subscriptions_users_model->get_subscriptions_by_id_user($user_id);
			}
		}
		foreach ($subscriptions_list as $key => $subcription) {
			if (isset($user_subscription[$subcription['id']])){
				$subscriptions_list[$key]['subscribed'] = 1;
			}
		}
		$CI->template_lite->assign('subscriptions_list', $subscriptions_list);
		$html = $CI->template_lite->fetch('helper_form_' . $place, 'user', 'subscriptions');
		return $html;
	}
}
if ( ! function_exists('get_user_subscriptions_list'))
{
	function get_user_subscriptions_list(){
		$CI = & get_instance();
		$CI->load->model('Subscriptions_model');
		$CI->load->model('subscriptions/models/Subscriptions_users_model');

		$subscriptions_list = $CI->Subscriptions_model->get_subscriptions_list(null, null, null, array('where' => array('subscribe_type' => 'user')));
		$user_id = $CI->session->userdata('user_id');
		if ($user_id){
			$user_subscription = $CI->Subscriptions_users_model->get_subscriptions_by_id_user($user_id);

			foreach ($subscriptions_list as $key => $subcription) {
				if (isset($user_subscription[$subcription['id']])){
					$subscriptions_list[$key]['subscribed'] = 1;
				}
			}
		}

		$CI->template_lite->assign('subscriptions_list', $subscriptions_list);
		$html = $CI->template_lite->fetch('user_subscription_list', 'user', 'subscriptions');
		return $html;
	}
}