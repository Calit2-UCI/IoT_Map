<?php
/**
* Social networking vkontakte service model
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

class Vkontakte_service_model extends Model {

	public $CI;
	public $api_url = 'https://api.vk.com/method/';

	function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
	}

	function get_user_data($user_id = 0, $access_key = '') {
		$this->CI->load->model('Social_networking_connections_model');
		$params = array(
			'access_key' => $access_key,
			'uid' => $user_id,
		);
		$response = $this->CI->Social_networking_connections_model->curl_get($this->api_url . 'getProfiles', $params);
		$data = @json_decode($response);
		$data = isset($data->response[0]) ? (array) $data->response[0] : false;
		if (isset($data['uid']) && isset($data['first_name']) && isset($data['last_name'])) {
			$user_data = array(
				'id' => $data['uid'],
				'fname' => $data['first_name'],
				'sname' => $data['last_name'],
			);
			return $user_data;
		}
		return false;
	}

}
