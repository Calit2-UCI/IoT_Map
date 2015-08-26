<?php
/**
* Social networking google service model
*
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Google_service_model extends Model {

	public $CI;
	public $api_url = 'https://www.googleapis.com/oauth2/v1/';

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	function get_user_data($user_id = 0, $access_key = '') {
		$this->CI->load->model('Social_networking_connections_model');
		$params = array(
			'access_token' => $access_key,
		);
		$response = $this->CI->Social_networking_connections_model->curl_get($this->api_url . 'userinfo', $params);
		$data = (array) @json_decode($response);
		if (isset($data['id']) && isset($data['email']) && isset($data['given_name']) && isset($data['family_name'])) {
			$user_data = array(
				'id' => $data['id'],
				'email' => $data['email'],
				'fname' => $data['given_name'],
				'sname' => $data['family_name'],
			);
			return $user_data;
		}
		return false;
	}

	function get_auth_params($service_data = array(), $redirect) {
		$app_key = isset($service_data['app_key']) ? $service_data['app_key'] : false;
		$params = array(
			'client_id' => $app_key,
			'redirect_uri' => site_url(),
			'state' => str_replace(site_url(), '/', $redirect),
			'response_type' => 'code',
			'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
		);
		ksort($params);
		return $params;
	}

	function get_token_params($service_data = array(), $redirect) {
		$app_key = isset($service_data['app_key']) ? $service_data['app_key'] : false;
		$app_secret = isset($service_data['app_secret']) ? $service_data['app_secret'] : false;
		$params = array(
			'client_id' => $app_key,
			'client_secret' => $app_secret,
			'redirect_uri' => site_url(),
			//'state' => str_replace(site_url(), '', $redirect),
			'grant_type' => 'authorization_code',
		);
		ksort($params);
		return $params;
	}

}
