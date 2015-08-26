<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!function_exists('check_pjax')) {

	function check_pjax() {
		$CI = &get_instance();
		if($CI->router->is_admin_class){
			$CI->template_lite->assign('use_pjax', 0);
			$CI->use_pjax = false;
		}else{
			$CI->template_lite->assign('use_pjax', 1);
			$CI->use_pjax = true;
		}
		if (INSTALL_MODULE_DONE) {
			if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX']){
				$CI->template_lite->assign('is_pjax', 1);
				$CI->is_pjax = true;
			}else{
				$CI->template_lite->assign('is_pjax', 0);
				$CI->is_pjax = false;
			}
		}
		return;
	}

}
