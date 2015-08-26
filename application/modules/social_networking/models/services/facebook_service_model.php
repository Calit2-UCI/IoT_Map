<?php
/**
* Social networking facebook service model
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

class Facebook_service_model extends Model {

	public $CI;
	public $api_url = 'https://graph.facebook.com/';
	
	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	function get_user_data($user_id = 0, $access_key = '') {
		$this->CI->load->model('Social_networking_connections_model');
		$params = array(
			'access_token' => $access_key,
			'fields' => 'id,first_name,last_name,email',
		);
		$response = $this->CI->Social_networking_connections_model->curl_get($this->api_url . 'me', $params);
		$data = (array) @json_decode($response);
		if (isset($data['id']) && isset($data['first_name']) && isset($data['last_name'])) {
			$user_data = array(
				'id' => $data['id'],
				'fname' => $data['first_name'],
				'sname' => $data['last_name'],
			);
			if(isset($data['email'])) $user_data['email'] = $data['email'];
			return $user_data;
		}
		return false;
	}

	/**
	 * Return auth parameters
	 * @param array $service_data service data
	 * @param string $redirect redirect urk
	 */
	public function get_auth_params($service_data=array(), $redirect){
		$app_key = isset($service_data['app_key']) ? $service_data['app_key'] : false;
		$params = array(
			'client_id' => $app_key,
			'redirect_uri' => $redirect,
			'response_type' => 'code',
			'scope' => 'email',
		);
		ksort($params);
		return $params;
	}
}
