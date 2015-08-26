<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!function_exists('check_oauth_response')) {

	function check_oauth_response() {
		if (INSTALL_MODULE_DONE) {

			$code = isset($_GET['code']) ? $_GET['code'] : false;
			if ($code) {
				$state = isset($_GET['state']) ? $_GET['state'] : false;
				if ($state)
					redirect(site_url($state . '?code=' . $code));
			}
		}
		return;
	}

}
