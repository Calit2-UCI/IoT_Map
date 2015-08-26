<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('fetch_menu_indicators')){

	function fetch_menu_indicators(){
		$CI = & get_instance();
		$CI->load->model('Menu_model');
		$CI->load->model('menu/models/Indicators_model');
		if(INSTALL_DONE) {
			if(!$CI->pg_module->get_module_config('menu', 'use_indicators')) {
				return false;
			}
			if($CI->session->userdata('auth_type')) {
				$CI->Menu_model->indicators = $CI->Indicators_model->get(
						$CI->session->userdata('auth_type'),
						$CI->session->userdata('user_id'));
			}
		}
	}

}