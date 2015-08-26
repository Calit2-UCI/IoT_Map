<?php
/**
* Social networking connections model
* 
* @package PG_Dating
* @subpackage application
* @category	modules
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Katya Kashkova <katya@pilotgroup.net>
* @version $Revision: 2 $ $Date: 2010-04-02 15:07:07 +0300 (Ср, 02 апр 2010) $ $Author: kkashkova $
**/

class Social_networking_connections_model extends Model {

	var $CI;

	function Social_networking_connections_model() {
		parent::Model();
		$this->CI = & get_instance();
		$this->CI->load->helper('social_networking');
	}

	public function curl_post($url, array $post = NULL, array $options = array()) {
		if (function_exists('curl_init')) {
			$defaults = array(
				CURLOPT_POST => 1,
				CURLOPT_POSTFIELDS => (string) http_build_query($post),
				CURLOPT_URL => $url,
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_TIMEOUT => 4,
				CURLOPT_SSL_VERIFYPEER => FALSE
			);
			$ch = curl_init();
			curl_setopt_array($ch, ($options + $defaults));
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
	}

	public function curl_get($url, array $get = NULL, array $options = array()) {
		if (function_exists('curl_init')) {
			$defaults = array(
				CURLOPT_URL => $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($get),
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_TIMEOUT => 4,
				CURLOPT_SSL_VERIFYPEER => FALSE
			);
			$ch = curl_init();
			curl_setopt_array($ch, ($options + $defaults));
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
	}

	function login($service_data = array(), $redirect = '') {
		$return = array("result" => "", "error" => "");
		// Данные
		$service_id = isset($service_data['id']) ? $service_data['id'] : false;
		if (isset($_GET['code'])) {
			$service_model = isset($service_data['gid']) ? $service_data['gid'] . '_service_model' : false;
			$service_file = $service_model ? APPPATH . 'modules/social_networking/models/services/' . $service_model . '.php' : false;
			if ($service_file && file_exists($service_file)) {
				include_once($service_file);
				$service = new $service_model();
				if (method_exists($service, 'get_token_params')) {
					$service_params = $service->get_token_params($service_data, $redirect);
				}
			}
			if (!isset($service_params)) {
				$params = array(
					'client_id' => isset($service_data['app_key']) ? $service_data['app_key'] : false,
					'client_secret' => isset($service_data['app_secret']) ? $service_data['app_secret'] : false,
					'code' => $_GET['code'],
					'redirect_uri' => $redirect,
				);
				ksort($params);
			} else {
				$params = $service_params;
				$params['code'] = $_GET['code'];
			}
			$res = $this->curl_post(isset($service_data['access_key_url']) ? $service_data['access_key_url'] : false, $params);
			$result = json_decode($res);
			if (!$result)
				parse_str($res, $result);
			$result = (array) $result;
			// Expires fix
			if (!isset($result['expires_in']) && isset($result['expires']))
				$result['expires_in'] = $result['expires'];
			$return['result'] = $result;

			if (isset($result['error_description']))
				$return['error'] = $result['error_description'];
			if (isset($result['error']->message))
				$return['error'] = $result['error']->message;
		}
		return $return;
	}

	function _check_oauth2_connection($service_data = array(), $redirect) {
		$service_model = isset($service_data['gid']) ? $service_data['gid'] . '_service_model' : false;
		$service_file = $service_model ? APPPATH . 'modules/social_networking/models/services/' . $service_model . '.php' : false;
		if ($service_file && file_exists($service_file)) {
			include_once($service_file);
			$service = new $service_model();
			if (method_exists($service, 'get_auth_params')) {
				$service_params = $service->get_auth_params($service_data, $redirect);
			}
		}
		$return = array("result" => "", "error" => "");
		$app_key = isset($service_data['app_key']) ? $service_data['app_key'] : false;
		$service_id = isset($service_data['id']) ? $service_data['id'] : false;
		$login_result = (array) count($_GET) > 0 ? $this->login($service_data, $redirect) : array('result' => '', 'error' => '');
		if ($login_result['error'])
			return $login_result;
		if (isset($login_result['result']['access_token']))
			return $login_result;
		$link = isset($service_data['authorize_url']) ? $service_data['authorize_url'] : false;
		if (isset($service_params)) {
			$params = $service_params;
		} else {
			$params = array(
				'client_id' => $app_key,
				'redirect_uri' => $redirect,
				'response_type' => 'code',
			);
			ksort($params);
		}
		$res = $this->curl_get($link, $params);
		$result = json_decode($res);
		if (isset($result->error_description))
			return array('result' => '', 'error' => $result->error_description);
		if (isset($result->error->message))
			return array('result' => '', 'error' => $result->error->message);
		if ($result == '') {
			$get = '';
			foreach ($params as $id => $value) {
				$get .= (strlen($get) == 0 ? '?' : '&') . $id . '=' . $value;
			}
			$link = $link . $get;
			redirect($link);
		}
		return $return;
	}

	function _check_oauth_connection($service_data = array(), $redirect) {
		$return = array("result" => "", "error" => "");
		$app_key = isset($service_data['app_key']) ? $service_data['app_key'] : false;
		$app_secret = isset($service_data['app_secret']) ? $service_data['app_secret'] : false;

		$service_model = isset($service_data['gid']) ? $service_data['gid'] . '_service_model' : false;
		$service_file = $service_model ? APPPATH . 'modules/social_networking/models/services/' . $service_model . '.php' : false;
		if ($service_file && file_exists($service_file)) {
			include_once($service_file);
			$service = new $service_model();
			$params = array('key' => $app_key, 'secret' => $app_secret);
			$service->oauth($params);
			if ($this->CI->session->userdata($service_data['gid'] . '_token_secret')) {
				$response = $service->get_access_token(false, $this->CI->session->userdata($service_data['gid'] . '_token_secret'));
				$this->CI->session->unset_userdata($service_data['gid'] . '_token_secret');
				if (!isset($response['oauth_token_secret']) || $response['oauth_token_secret'] == '') {
					$return['error'] = l('empty_application', 'social_networking');
				} else {
					$return['result'] = $response;
				}
			} else {
				$response = $service->get_request_token($redirect);
				if (!isset($response['token_secret']) || $response['token_secret'] == '') {
					$return['error'] = l('empty_application', 'social_networking');
				} else {
					$this->CI->session->set_userdata($service_data['gid'] . '_token_secret', $response['token_secret']);
					redirect($response['redirect']);
				}
			}
		}
		return $return;
	}

}
